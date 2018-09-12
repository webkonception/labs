<?php namespace App\Http\Controllers\BoatgestAdmin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\AdsCaracts;
use App\Enquiry;
use App\BodCaracts;
use App\ContactForm;
use App\ProspectiveCustomers;

use App\CustomersCaracts;
use App\CommercialsCaracts;
use App\DealersCaracts;
use App\PrivatesCaracts;
use App\Http\Requests\UpdateCustomersCaractsRequest;
use App\Http\Requests\UpdateCommercialsCaractsRequest;
use App\Http\Requests\UpdateDealersCaractsRequest;
use App\Http\Requests\UpdatePrivatesCaractsRequest;

use App\Roles;
use App\Countries;

use App\Role;
use App\User;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use QueryException;

use DB;
use Auth;
use Carbon\Carbon;

use ConsoleTVs\Charts\Facades\Charts as Charts;
//use Spatie\Analytics\AnalyticsFacade as Analytics;
//use Spatie\Analytics\Period;

use Spatie\LaravelAnalytics\LaravelAnalyticsFacade as LaravelAnalytics;
use Illuminate\Support\Collection;

class StatisticsController extends Controller {

	protected $startDate_last_week;
	protected $endDate_last_week;

	protected $startDate_current_week;
	protected $endDate_current_week;

	protected $startDate_last_month;
	protected $endDate_last_month;

	protected $startDate_current_month;
	protected $endDate_current_month;

	protected $startDate_last_year;
	protected $endDate_last_year;

	protected $startDate_current_year;
	protected $endDate_current_year;

	protected $formatDate;
	protected $formatDOW;
	protected $formatDateByMonth;
	protected $formatDateByMonthName;

	public function __construct()
	{
		$this->startDate_last_week = Carbon::now()->subWeek()->startOfWeek();
		$this->endDate_last_week = Carbon::now()->subWeek()->endOfWeek();

		$this->startDate_current_week = Carbon::now()->startOfWeek();
		$this->endDate_current_week = Carbon::now()->endOfWeek();
		//$this->endDate_current_week = Carbon::now();

		//$this->startDate_last_month = Carbon::parse('-1 months')->startOfMonth();
		//$this->endDate_last_month =  Carbon::parse('-1 months')->endOfMonth();
		$this->startDate_last_month = Carbon::now()->addMonthNoOverflow(-1)->startOfMonth();
		$this->endDate_last_month =  Carbon::now()->addMonthNoOverflow(-1)->endOfMonth();

		$this->startDate_current_month = Carbon::now()->startOfMonth();
		$this->endDate_current_month = Carbon::now()->endOfMonth();
		//$this->endDate_current_month = Carbon::now();

		$this->startDate_last_year = Carbon::parse('-1 years')->startOfYear();
		$this->endDate_last_year =  Carbon::parse('-1 years')->endOfYear();

		$this->startDate_current_year = Carbon::now()->startOfYear();
		$this->endDate_current_year = Carbon::now()->endOfYear();
		//$this->endDate_current_year = Carbon::now();

		$this->formatDate = function ($value) {
			//return $value->toDateTimeString();
			if($value) {
				return $value->format("Y-m-d");
			} else {
				return '-';
			}
		};

		$this->formatDOW = function ($value) {
			if($value) {
				return $value->format("l");
			} else {
				return '-';
			}
		};

		$this->formatDateByMonth = function ($value) {
			if($value) {
				return $value->format("Y-m");
			} else {
				return '-';
			}
		};

		$this->formatDateByMonthName = function ($value) {
			if($value) {
				return $value->format("F");
			} else {
				return '-';
			}
		};
	}

	/**
	 * Index page
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function getIndex()
    {
		//return view(config('quickadmin.route') . '.statistics.index');
	}

	public function index (Request $request) {
		$currentLocale = !empty(config('app.country_code')) ? config('app.country_code') : 'uk';
		$website_youboat_url = config('youboat.' . $currentLocale . '.website_youboat_url');

		$routeParameters = $request->route()->parameters();
		$input = $request->all();
		$analyticsData = [];

		$current_year = Carbon::now()->year;

		$EffectiveUrl = '';
		////////
		// PATH
		$path = array_key_exists('statistics', $routeParameters) ? '/' . $routeParameters['statistics'] : (array_key_exists('path', $routeParameters) ? '/' . $routeParameters['path'] : '');

		/////////
		// AD ID
		$ad_id = '';
		if(array_key_exists('statistics', $routeParameters) && preg_match('/^ad-[0-9]+$/', $routeParameters['statistics'])) {
			$ad_id = preg_replace('/ad-/', '', $routeParameters['statistics']);
			$path = '/' . $ad_id;
		} /*elseif(array_key_exists('ad_id', $routeParameters) && preg_match('/^ad-[0-9]+$/', $routeParameters['ad_id'])) {
			$ad_id = preg_replace('/ad-/', '', $routeParameters['ad_id']);
			//$path = $ad_id;
		}*/

		// AD STATS EVENTS
		$input['ad_id'] = $ad_id;
		$adEvents = [];
		$TotalAdStatsEvents = [];
		//
		if(isset($input['ad_id']) && !empty($input['ad_id'])) {
			$adStatsEvents = $this->adStatsEvents($input['ad_id']);
			if(!empty($adStatsEvents)) {
				$adEvents = $adStatsEvents->toArray();
			}
		} else {
			//$Total['events'] = $this->adStatsEvents();
			$startDate = $this->startDate_current_year;
			$endDate = $this->endDate_current_year;

			$adStatsEvents = $this->adStatsEvents('', true, $startDate, $endDate);
			if(!empty($adStatsEvents)) {
				$Total['events'] = $adStatsEvents->toArray();
			}
			$adStatsEvents = $this->adStatsEvents('', true, $startDate, $endDate, 'date');
			if(!empty($adStatsEvents)) {
				$TotalAdStatsEvents = $adStatsEvents->toArray();
			}
		}

		//if(empty($input['ad_id'])) {
		if(empty($ad_id) && !empty($path) && preg_match('/\.*?([0-9]+)$/', $path, $matches)) {
			$ad_id = $matches[0];
		}
		$input['path'] = $path;

		$chartTotalVisitorsAndPageViewsByPath = [];
		if(isset($input['path']) && !empty($input['path']) && empty($input['ad_id'])) {
			$chartTotalVisitorsAndPageViewsByPath = $this->chartTotalVisitorsAndPageViews($input['path']);
			$EffectiveUrl = $website_youboat_url . $input['path'];
		} elseif(isset($input['ad_id']) && !empty($input['ad_id'])) {
			$EffectiveUrl = getEffectiveUrl(url($website_youboat_url . '/buy/type/manufacturer/model/' . $input['ad_id']));
			if(preg_match('/\/' . $input['ad_id'] . '/',$EffectiveUrl)) {
				$chartTotalVisitorsAndPageViewsByPath = $this->chartTotalVisitorsAndPageViews($input['ad_id'], ['EffectiveUrl' => str_replace($website_youboat_url, '', $EffectiveUrl)]);
			} else {
				$EffectiveUrl = '';
			}
		} else {

			/////////////////////////////
			// TotalVisitorsAndPageViews
			/**
			 * getVisitorsAndPageViewsForPeriod
			 * Get the amount of visitors and pageviews for the given period
			 * $analyticsData now contains a Collection with 3 columns: "date" or "yearMonth", "visitors" and "pageViews"
			 */
			$chartTotalVisitorsAndPageViews = $this->chartTotalVisitorsAndPageViews();

			///////////////
			///////////////
			// TopKeyWords
			/*
			 * getTopKeyWordsForPeriod
			 * Get the top keywords for the given period
			 * $analyticsData now contains a Collection with 2 columns: "keyword" and "sessions"
			 */
			/****
			$maxResults = 30;
			$analyticsData['LastWeek']['TopKeyWords'] = LaravelAnalytics::getTopKeyWordsForPeriod($this->startDate_last_week, $this->endDate_last_week, $maxResults);
			$analyticsData['CurrentWeek']['TopKeyWords'] = LaravelAnalytics::getTopKeyWordsForPeriod($this->startDate_current_week, $this->endDate_current_week, $maxResults);

			$maxResults = 30;
			$analyticsData['LastMonth']['TopKeyWords'] = LaravelAnalytics::getTopKeyWordsForPeriod($this->startDate_last_month, $this->endDate_last_month, $maxResults);
			$analyticsData['CurrentMonth']['TopKeyWords'] = LaravelAnalytics::getTopKeyWordsForPeriod($this->startDate_current_month, $this->endDate_current_month, $maxResults);

			$maxResults = 30;
			$analyticsData['LastYear']['TopKeyWords'] = LaravelAnalytics::getTopKeyWordsForPeriod($this->startDate_last_year, $this->endDate_last_year, $maxResults);
			$analyticsData['CurrentYear']['TopKeyWords'] = LaravelAnalytics::getTopKeyWordsForPeriod($this->startDate_current_year, $this->endDate_current_year, $maxResults);

				// LastWeek
				$LastWeekTopKeyWords = $analyticsData['LastWeek']['TopKeyWords'];
				$LastWeekTopKeyWordsKeyword = $LastWeekTopKeyWords->lists('keyword')->toArray();
				$LastWeekTopKeyWordsSessions = $LastWeekTopKeyWords->lists('sessions')->toArray();

				// CurrentWeek
				$CurrentWeekTopKeyWords = $analyticsData['CurrentWeek']['TopKeyWords'];
				$CurrentWeekTopKeyWordsKeywordsKeyword = $CurrentWeekTopKeyWords->lists('keyword')->toArray();
				$CurrentWeekTopKeyWordsKeywordsSessions = $CurrentWeekTopKeyWords->lists('sessions')->toArray();

				// LastMonth
				$LastMonthTopKeyWords = $analyticsData['LastMonth']['TopKeyWords'];
				$LastMonthTopKeyWordsKeyword = $LastMonthTopKeyWords->lists('keyword')->toArray();
				$LastMonthTopKeyWordsSessions = $LastMonthTopKeyWords->lists('sessions')->toArray();

				// CurrentMonth
				$CurrentMonthTopKeyWords = $analyticsData['CurrentMonth']['TopKeyWords'];
				$CurrentMonthTopKeyWordsKeyword = $CurrentMonthTopKeyWords->lists('keyword')->toArray();
				$CurrentMonthTopKeyWordsSessions = $CurrentMonthTopKeyWords->lists('sessions')->toArray();

				// LastYear
				$LastYearTopKeyWords = $analyticsData['LastYear']['TopKeyWords'];
				$LastYearTopKeyWordsKeyword = $LastYearTopKeyWords->lists('keyword')->toArray();
				$LastYearTopKeyWordsSessions = $LastYearTopKeyWords->lists('sessions')->toArray();

				// CurrentYear
				$CurrentYearTopKeyWords = $analyticsData['CurrentYear']['TopKeyWords'];
				$CurrentYearTopKeyWordsKeyword = $CurrentYearTopKeyWords->lists('keyword')->toArray();
				$CurrentYearTopKeyWordsSessions = $CurrentYearTopKeyWords->lists('sessions')->toArray();
			 ****/

			////////////////
			// TopReferrers
			/**
			 * getTopReferrersForPeriod
			 * Get the top referrers for the given period
			 * $analyticsData now contains a Collection with 2 columns: "url" and "pageViews"
			 */
			/****
			$maxResults = 30;
			$analyticsData['LastWeek']['TopReferrers'] = LaravelAnalytics::getTopReferrersForPeriod($this->startDate_last_week, $this->endDate_last_week, $maxResults);
			$analyticsData['CurrentWeek']['TopReferrers'] = LaravelAnalytics::getTopReferrersForPeriod($this->startDate_current_week, $this->endDate_current_week, $maxResults);

			$maxResults = 30;
			$analyticsData['LastMonth']['TopReferrers'] = LaravelAnalytics::getTopReferrersForPeriod($this->startDate_last_month, $this->endDate_last_month, $maxResults);
			$analyticsData['CurrentMonth']['TopReferrers'] = LaravelAnalytics::getTopReferrersForPeriod($this->startDate_current_month, $this->endDate_current_month, $maxResults);

			$maxResults = 30;
			$analyticsData['LastYear']['TopReferrers'] = LaravelAnalytics::getTopReferrersForPeriod($this->startDate_last_year, $this->endDate_last_year, $maxResults);
			$analyticsData['CurrentYear']['TopReferrers'] = LaravelAnalytics::getTopReferrersForPeriod($this->startDate_current_year, $this->endDate_current_year, $maxResults);

				// LastWeek
				$LastWeekTopReferrers = $analyticsData['LastWeek']['TopReferrers'];
				$LastWeekTopReferrersUrl = $LastWeekTopReferrers->lists('url')->toArray();
				$LastWeekTopReferrersPageViews = $LastWeekTopReferrers->lists('pageViews')->toArray();

				// CurrentWeek
				$CurrentWeekTopReferrers = $analyticsData['CurrentWeek']['TopReferrers'];
				$CurrentWeekTopReferrersUrl = $CurrentWeekTopReferrers->lists('url')->toArray();
				$CurrentWeekTopReferrersPageViews = $CurrentWeekTopReferrers->lists('pageViews')->toArray();

				// LastMonth
				$LastMonthTopReferrers = $analyticsData['LastMonth']['TopReferrers'];
				$LastMonthTopReferrersUrl = $LastMonthTopReferrers->lists('url')->toArray();
				$LastMonthTopReferrersPageViews = $LastMonthTopReferrers->lists('pageViews')->toArray();

				// CurrentMonth
				$CurrentMonthTopReferrers = $analyticsData['CurrentMonth']['TopReferrers'];
				$CurrentMonthTopReferrersUrl = $CurrentMonthTopReferrers->lists('url')->toArray();
				$CurrentMonthTopReferrersPageViews = $CurrentMonthTopReferrers->lists('pageViews')->toArray();

				// LastYear
				$LastYearTopReferrers = $analyticsData['LastYear']['TopReferrers'];
				$LastYearTopReferrersUrl = $LastYearTopReferrers->lists('url')->toArray();
				$LastYearTopReferrersPageViews = $LastYearTopReferrers->lists('pageViews')->toArray();

				// CurrentYear
				$CurrentYearTopReferrers = $analyticsData['CurrentYear']['TopReferrers'];
				$CurrentYearTopReferrersUrl = $CurrentYearTopReferrers->lists('url')->toArray();
				$CurrentYearTopReferrersPageViews = $CurrentYearTopReferrers->lists('pageViews')->toArray();
			 ****/

			///////////////
			// TopBrowsers
			/**
			 * getTopBrowsersForPeriod
			 * Get the top browsers for the given period
			 * $analyticsData now contains a Collection with 2 columns: "browser" and "sessions"
			 */
			/****
			$maxResults = 6;
			$analyticsData['LastWeek']['TopBrowsers'] = LaravelAnalytics::getTopBrowsersForPeriod($this->startDate_last_week, $this->endDate_last_week, $maxResults);
			$analyticsData['CurrentWeek']['TopBrowsers'] = LaravelAnalytics::getTopBrowsersForPeriod($this->startDate_current_week, $this->endDate_current_week, $maxResults);

			$maxResults = 6;
			$analyticsData['LastMonth']['TopBrowsers'] = LaravelAnalytics::getTopBrowsersForPeriod($this->startDate_last_month, $this->endDate_last_month, $maxResults);
			$analyticsData['CurrentMonth']['TopBrowsers'] = LaravelAnalytics::getTopBrowsersForPeriod($this->startDate_current_month, $this->endDate_current_month, $maxResults);

			$maxResults = 6;
			$analyticsData['LastYear']['TopBrowsers'] = LaravelAnalytics::getTopBrowsersForPeriod($this->startDate_last_year, $this->endDate_last_year, $maxResults);
			$analyticsData['CurrentYear']['TopBrowsers'] = LaravelAnalytics::getTopBrowsersForPeriod($this->startDate_current_year, $this->endDate_current_year, $maxResults);

				// LastWeek
				$LastWeekTopBrowsers = $analyticsData['LastWeek']['TopBrowsers'];
				$LastWeekTopBrowsersBrowser = $LastWeekTopBrowsers->lists('browser')->toArray();
				$LastWeekTopBrowsersSessions = $LastWeekTopBrowsers->lists('sessions')->toArray();

				// CurrentWeek
				$CurrentWeekTopBrowsers = $analyticsData['CurrentWeek']['TopBrowsers'];
				$CurrentWeekTopBrowsersBrowser = $CurrentWeekTopBrowsers->lists('browser')->toArray();
				$CurrentWeekTopBrowsersSessions = $CurrentWeekTopBrowsers->lists('sessions')->toArray();

				// LastMonth
				$LastMonthTopBrowsers = $analyticsData['LastMonth']['TopBrowsers'];
				$LastMonthTopBrowsersBrowser = $LastMonthTopBrowsers->lists('browser')->toArray();
				$LastMonthTopBrowsersSessions = $LastMonthTopBrowsers->lists('sessions')->toArray();

				// CurrentMonth
				$CurrentMonthTopBrowsers = $analyticsData['CurrentMonth']['TopBrowsers'];
				$CurrentMonthTopBrowsersBrowser = $CurrentMonthTopBrowsers->lists('browser')->toArray();
				$CurrentMonthTopBrowsersSessions = $CurrentMonthTopBrowsers->lists('sessions')->toArray();

				// LastYear
				$LastYearTopBrowsers = $analyticsData['LastYear']['TopBrowsers'];
				$LastYearTopBrowsersBrowser = $LastYearTopBrowsers->lists('browser')->toArray();
				$LastYearTopBrowsersSessions = $LastYearTopBrowsers->lists('sessions')->toArray();

				// CurrentYear
				$CurrentYearTopBrowsers = $analyticsData['CurrentYear']['TopBrowsers'];
				$CurrentYearTopBrowsersBrowser = $CurrentYearTopBrowsers->lists('browser')->toArray();
				$CurrentYearTopBrowsersSessions = $CurrentYearTopBrowsers->lists('sessions')->toArray();
			 ****/

			////////////////////
			// MostVisitedPages
			/*
			 * getMostVisitedPagesForPeriod
			 * Get the most visited pages for the given period
			 * Get the most visited pages
			 * $analyticsData now contains a Collection with 2 columns: "url" and "pageViews"
			 */
			/****
			$maxResults = 30;
			$analyticsData['LastWeek']['MostVisitedPages'] = LaravelAnalytics::getMostVisitedPagesForPeriod($this->startDate_last_week, $this->endDate_last_week, $maxResults);
			$analyticsData['CurrentWeek']['MostVisitedPages'] = LaravelAnalytics::getMostVisitedPagesForPeriod($this->startDate_current_week, $this->endDate_current_week, $maxResults);

			$maxResults = 30;
			$analyticsData['LastMonth']['MostVisitedPages'] = LaravelAnalytics::getMostVisitedPagesForPeriod($this->startDate_last_month, $this->endDate_last_month, $maxResults);
			$analyticsData['CurrentMonth']['MostVisitedPages'] = LaravelAnalytics::getMostVisitedPagesForPeriod($this->startDate_current_month, $this->endDate_current_month, $maxResults);

			$maxResults = 30;
			$analyticsData['LastYear']['MostVisitedPages'] = LaravelAnalytics::getMostVisitedPagesForPeriod($this->startDate_last_year, $this->endDate_last_year, $maxResults);
			$analyticsData['CurrentYear']['MostVisitedPages'] = LaravelAnalytics::getMostVisitedPagesForPeriod($this->startDate_current_year, $this->endDate_current_year, $maxResults);

				// LastWeek
				$LastWeekMostVisitedPages = $analyticsData['LastWeek']['MostVisitedPages'];
				$LastWeekMostVisitedPagesUrl = $LastWeekMostVisitedPages->lists('url')->toArray();
				$LastWeekMostVisitedPagesPageViews = $LastWeekMostVisitedPages->lists('pageViews')->toArray();

				// CurrentWeek
				$CurrentWeekMostVisitedPages = $analyticsData['CurrentWeek']['MostVisitedPages'];
				$CurrentWeekMostVisitedPagesUrl = $CurrentWeekMostVisitedPages->lists('url')->toArray();
				$CurrentWeekMostVisitedPagesPageViews = $CurrentWeekMostVisitedPages->lists('pageViews')->toArray();

				// LastMonth
				$LastMonthMostVisitedPages = $analyticsData['LastMonth']['MostVisitedPages'];
				$LastMonthMostVisitedPagesUrl = $LastMonthMostVisitedPages->lists('url')->toArray();
				$LastMonthMostVisitedPagesPageViews = $LastMonthMostVisitedPages->lists('pageViews')->toArray();

				// CurrentMonth
				$CurrentMonthMostVisitedPages = $analyticsData['CurrentMonth']['MostVisitedPages'];
				$CurrentMonthMostVisitedPagesUrl = $CurrentMonthMostVisitedPages->lists('url')->toArray();
				$CurrentMonthMostVisitedPagesPageViews = $CurrentMonthMostVisitedPages->lists('pageViews')->toArray();

				// LastYear
				$LastYearMostVisitedPages = $analyticsData['LastYear']['MostVisitedPages'];
				$LastYearMostVisitedPagesUrl = $LastYearMostVisitedPages->lists('url')->toArray();
				$LastYearMostVisitedPagesPageViews = $LastYearMostVisitedPages->lists('pageViews')->toArray();

				// CurrentYear
				$CurrentYearMostVisitedPages = $analyticsData['CurrentYear']['MostVisitedPages'];
				$CurrentYearMostVisitedPagesUrl = $CurrentYearMostVisitedPages->lists('url')->toArray();
				$CurrentYearMostVisitedPagesPageViews = $CurrentYearMostVisitedPages->lists('pageViews')->toArray();
			****/

			///////////////
			// ActiveUsers
			/*
			 * getActiveUsers
			 * Get the number of active users currently on the site
			 * $analyticsData now contains a Collection the amount of visitors that is viewing your site right now.
			 */
			/****
			$analyticsData['ActiveUsers'] = LaravelAnalytics::getActiveUsers();
			 ****/

			////////////
			// PROSPECTIVECUSTOMERS
			//$ProspectiveCustomers = ProspectiveCustomers::all('id', 'created_at');
			$WhereRaw = "country_code = '" . $currentLocale . "'";
			$ProspectiveCustomers = ProspectiveCustomers::select('id', 'created_at')->whereRaw($WhereRaw)->get();
			$Total['prospective_customers'] = $ProspectiveCustomers->count();

			//die();
			$chartProspectiveCustomersByMonth = Charts::database($ProspectiveCustomers, 'area', 'chartjs')
				->title("Prospective Customers total requests by month" . ' (' . $ProspectiveCustomers->count() . ')')
				->elementLabel("Total")
				->responsive(true)
				->colors(['#8BC34A']) // green
				//->dateFormat('j F y')
				->lastByMonth();

			//$ProspectiveCustomersReferrer = ProspectiveCustomers::distinct()->select('referrer')->groupBy('referrer')->get();
			$WhereRaw = "country_code = '" . $currentLocale . "'";
			$ProspectiveCustomersReferrer = ProspectiveCustomers::select(DB::raw('count(*) as count, referrer'))
				->whereRaw($WhereRaw)
				->whereNotNull('referrer')
				->groupby('referrer')
				//->distinct()
				->orderBy('referrer', 'ASC')
				->get();

			$label = [];
			$values = [];
			foreach($ProspectiveCustomersReferrer->toArray() as $referrers) {
				array_push($label, $referrers['referrer'] . ' : ' . $referrers['count']);
				array_push($values, $referrers['count']);
			}
			$chartProspectiveCustomersPie = Charts::create('pie', 'c3')
				->title("Prospective Customers Total" . ' (' . array_sum($values) . ')')
				->elementLabel("Total")
				->labels($label)
				->values($values)
				->color_pattern(['#8BC34A', '#FFC107', '#F44336', '#2196F3', '#607D8B', '#3295BD', '#A5488B', '#DCAD66']) // green, yellow, red, blue, blue grey, bleu grey green, purple, light brown
				//['#1f77b4', '#aec7e8', '#ff7f0e', '#ffbb78', '#2ca02c', '#98df8a', '#d62728', '#ff9896', '#9467bd', '#c5b0d5', '#8c564b', '#c49c94', '#e377c2', '#f7b6d2', '#7f7f7f', '#c7c7c7', '#bcbd22', '#dbdb8d', '#17becf', '#9edae5']
				->responsive(true);

			////////////
			// CONTACT
			$ContactForm = ContactForm::all('id', 'created_at');
			$Total['contact'] = $ContactForm->count();

			//var_dump($ContactForm->toArray());
			//die();
			$chartContact = Charts::database($ContactForm, 'area', 'chartjs')
				->title("Contact requests" . ' (' . $ContactForm->count() .') ')
				->elementLabel("Total")
				->responsive(true)
				//->groupByMonth($current_year, true);
				//->groupByMonth();
				->lastByMonth();

			////////////
			// ENQUIRY
			//$Enquiry = Enquiry::all('id', 'created_at');
			$WhereRaw = "country_code = '" . $currentLocale . "'";
			$Enquiry = Enquiry::select('id', 'created_at')->whereRaw($WhereRaw)->get();
			$Total['enquiry'] = $Enquiry->count();
			//$BodCaracts = BodCaracts::all('id', 'created_at');
			$BodCaracts = BodCaracts::select('id', 'created_at')->whereRaw($WhereRaw)->get();
			$BODsTotal = $BodCaracts->count();
			$Total['bods'] = $BODsTotal;

			//$BodsTransfo = BodCaracts::where('reference', 'LIKE', '%bod_enquiry%')->get();
			$BodsTransfo = BodCaracts::select('id', 'created_at')->whereRaw($WhereRaw)->where('reference', 'LIKE', '%bod_enquiry%')->get();
			$Total['bods_transfo'] = $BodsTransfo->count();

			//$chartEnquiryBOD = Charts::multiDatabase('area', 'chartjs')
			//$chartEnquiryBOD = Charts::multiDatabase('bar', 'c3')
			$chartEnquiryBOD = Charts::multiDatabase('bar', 'chartjs')
				->title("Enquiry" . ' (' . $Total['enquiry'] .') ' .
					"Transfo". ' (' . $Total['bods_transfo'] .') ' . "BOD". ' (' . $Total['bods'] .') ')
				->elementLabel("Total")
				->responsive(true)
				->dataset('Total Enquiry', $Enquiry)
				->dataset('Total Transfo', $BodsTransfo)
				->dataset('Total BOD', $BodCaracts)
				->colors(['#8BC34A', '#FFC107', '#F44336', '#2196F3', '#607D8B', '#3295BD', '#A5488B', '#DCAD66']) // green, yellow, red, blue, blue grey, bleu grey green, purple, light brown
				//->groupByMonth($current_year, true);
				//->groupByMonth()
				->lastByMonth();

			////////////
			// BODs
			//$BodCaractsValid = BodCaracts::where('status', 'valid')->get();
			$BodCaractsValid = BodCaracts::select('id', 'created_at')->whereRaw($WhereRaw)->where('status', 'valid')->get();
			//$BodCaractsInModeration = BodCaracts::where('status', 'in_moderation')->get();
			$BodCaractsInModeration = BodCaracts::select('id', 'created_at')->whereRaw($WhereRaw)->where('status', 'in_moderation')->get();
			//$BodCaractsUnpublished = BodCaracts::where('status', 'unpublished')->get();
			$BodCaractsUnpublished = BodCaracts::select('id', 'created_at')->whereRaw($WhereRaw)->where('status', 'unpublished')->get();

			$count['BodCaractsValid'] = $BodCaractsValid->count();
			$count['BodCaractsInModeration'] = $BodCaractsInModeration->count();
			$count['BodCaractsUnpublished'] = $BodCaractsUnpublished->count();

			$chartBODsByMonth = Charts::multiDatabase('bar', 'chartjs')
				->title("BODs by month" . ' (' . ($BodCaractsValid->count()+$BodCaractsInModeration->count()+$BodCaractsUnpublished->count()) . ')')
				->elementLabel("Total")
				->stacked(true)
				->responsive(true)
				->dataset('Total' . ' (' . $count['BodCaractsValid'] . ') ' . 'Valid', $BodCaractsValid)
				->dataset('Total' . ' (' . $count['BodCaractsInModeration'] . ') ' . 'In Moderation', $BodCaractsInModeration)
				->dataset('Total' . ' (' . $count['BodCaractsUnpublished'] . ') ' . 'Unpublished', $BodCaractsUnpublished)
				->colors(['#8BC34A', '#FFC107', '#F44336', '#2196F3', '#607D8B', '#3295BD', '#A5488B', '#DCAD66']) // green, yellow, red, blue, blue grey, bleu grey green, purple, light brown
				//->groupByMonth($current_year, true);
				->groupByMonth();
				//->lastByMonth();

			$chartBODsTotalPie = Charts::create('pie', 'c3')
				->title("BODs Total". ' (' . ($BodCaractsValid->count()+$BodCaractsInModeration->count()+$BodCaractsUnpublished->count()) . ')')
				->elementLabel("Total")
				->labels(
					[
						'Total Valid' . ' : ' . $count['BodCaractsValid'],
						'Total In Moderation' . ' : ' . $count['BodCaractsInModeration'],
						'Total Unpublished' . ' : ' . $count['BodCaractsUnpublished']
					]
				)
				->values(
					[
						$count['BodCaractsValid'],
						$count['BodCaractsInModeration'],
						$count['BodCaractsUnpublished']
					]
				)
				->color_pattern(['#8BC34A', '#FFC107', '#F44336', '#2196F3', '#607D8B', '#3295BD', '#A5488B', '#DCAD66']) // green, yellow, red, blue, blue grey, bleu grey green, purple, light brown
				->responsive(true);

			////////////
			// Globalchart
			$count['Enquiry'] = $Enquiry->count();
			$count['BodsTransfo'] = $BodsTransfo->count();
			$count['Bods'] = $BodCaracts->count();
			$count['Contact'] = $ContactForm->count();

			$chartGlobal = Charts::multiDatabase('line', 'chartjs')
				->title("Total Acquisitions by month")
				->elementLabel("Total")
				//->responsive(true)
				->dimensions(0, 300)// Width x Height
				->dataset('Total' . ' (' . $count['Enquiry'] . ') ' . 'Enquiry mail sent', $Enquiry)
				->dataset('Total' . ' (' . $count['BodsTransfo'] . ') ' . 'Enquiry Transfo to BOD', $BodsTransfo)
				->dataset('Total' . ' (' . $count['Bods'] . ') ' . 'Bods created', $BodCaracts)
				->dataset('Total' . ' (' . $count['Contact'] . ') ' . 'Contact mail', $ContactForm)
				->colors(['#8BC34A', '#FFC107', '#F44336', '#2196F3', '#607D8B', '#3295BD', '#A5488B', '#DCAD66'])// green, yellow, red, blue, blue grey, bleu grey green, purple, light brown
				//->groupByMonth($current_year, false);
				//->groupByMonth();
				->lastByMonth('12', false);

			$chartGlobalPie = Charts::create('pie', 'c3')
				->title("Total Acquisitions")
				->elementLabel("Total")
				->labels(
					[
						'Total Enquiry mail sent' . ' : ' . $count['Enquiry'],
						'Total Enquiry Transfo to BOD' . ' : ' . $count['BodsTransfo'],
						'Total Bods created' . ' : ' . $count['Bods'],
						'Total Contact mail' . ' : ' . $count['Contact']
					]
				)
				->values(
					[
						$count['Enquiry'],
						$count['BodsTransfo'],
						$count['Bods'],
						$count['Contact']
					]
				)
				->color_pattern(['#8BC34A', '#FFC107', '#F44336', '#2196F3', '#607D8B', '#3295BD', '#A5488B', '#DCAD66']) // green, yellow, red, blue, blue grey, bleu grey green, purple, light brown
				->dimensions(0, 300);// Width x Height

			$labelDates = [];

			ksort($TotalAdStatsEvents["view_the_phone_number"]);
			ksort($TotalAdStatsEvents["send_enquiry"]);
			ksort($TotalAdStatsEvents["dealer_details"]);
			ksort($TotalAdStatsEvents["sell"]);
			$TotalAdStatsEvents = [
				"view_the_phone_number" => $TotalAdStatsEvents["view_the_phone_number"],
				"send_enquiry" => $TotalAdStatsEvents["send_enquiry"],
				"dealer_details" => $TotalAdStatsEvents["dealer_details"],
				"sell" => $TotalAdStatsEvents["sell"]
			];

			// create an array for dates
			foreach ($TotalAdStatsEvents as $event_type => $datas) {
				foreach ($datas as $data => $v) {
					array_push($labelDates, $data);
				}
			}
			asort($labelDates);
			$labelDates = array_unique($labelDates, SORT_REGULAR);
			//$labelDates = array_map($this->formatDateByMonth, $labelDates);

			// add dates keys missing
			foreach ($TotalAdStatsEvents as $event_type => $datas) {
				foreach ($labelDates as $date) {
					if (!array_key_exists($date, $TotalAdStatsEvents[$event_type])) {
						$TotalAdStatsEvents[$event_type][$date] = 0;
						ksort($TotalAdStatsEvents[$event_type]);
					}
				}
			}

			// Format dates
			$labelDates = array_map(function ($date) {
				return date('Y-m-d', strtotime($date));
			}, $labelDates);

			//$chartGlobalEvents = Charts::multi('mix', 'chartjs')
			//$chartGlobalEvents = Charts::multi('bar', 'chartjs')
			$count['view_the_phone_number'] = array_sum(array_values($TotalAdStatsEvents['view_the_phone_number']));
			$count['send_enquiry'] = array_sum(array_values($TotalAdStatsEvents['send_enquiry']));
			$count['dealer_details'] = array_sum(array_values($TotalAdStatsEvents['dealer_details']));
			$count['sell'] = array_sum(array_values($TotalAdStatsEvents['sell']));
			$chartGlobalEvents = Charts::multi('line', 'chartjs')
				->title("Total clicks's events")
				->elementLabel("Total")
				->dimensions(0, 400)
				->dataset('Total' . ' (' . $count['view_the_phone_number'] . ')' . ' View the phone number', array_values($TotalAdStatsEvents['view_the_phone_number']))
				->dataset('Total' . ' (' . $count['send_enquiry'] . ')' . ' Send Enquiry', array_values($TotalAdStatsEvents['send_enquiry']))
				->dataset('Total' . ' (' . $count['dealer_details'] . ')' . ' Look Dealer Details', array_values($TotalAdStatsEvents['dealer_details']))
				->dataset('Total' . ' (' . $count['sell'] . ')' . ' Autopromo Sell Banners', array_values($TotalAdStatsEvents['sell']))
				->labels($labelDates)
				->colors(['#8BC34A', '#FFC107', '#F44336', '#2196F3', '#607D8B', '#3295BD', '#A5488B', '#DCAD66']) // green, yellow, red, blue, blue grey, bleu grey green, purple, light brown
				->responsive(false);

			$chartGlobalEventsPie = Charts::create('pie', 'c3')
				->title("Total  clicks's events")
				->elementLabel("Total")
				->labels(
					[
						'Total View the phone number' . ' : ' . $count['view_the_phone_number'],
						'Total Send Enquiry' . ' : ' . $count['send_enquiry'],
						'Total Look Dealer Details' . ' : ' . $count['dealer_details'],
						'Total Autopromo Sell Banners' . ' : ' . $count['sell']
					]
				)
				->values(
					[
						$count['view_the_phone_number'],
						$count['send_enquiry'],
						$count['dealer_details'],
						$count['sell']
					]
				)
				->color_pattern(['#8BC34A', '#FFC107', '#F44336', '#2196F3', '#607D8B', '#3295BD', '#A5488B', '#DCAD66']) // green, yellow, red, blue, blue grey, bleu grey green, purple, light brown
				->dimensions(0, 300);// Width x Height

			////////////
			// Ads
			//$AdsCaracts = AdsCaracts::where('status', 'active')->all('id', 'created_at');
			//$WhereRaw = "(created_at BETWEEN '" . $this->startDate_current_year->format("Y-m-d 00:00:00") . "' AND '" . $this->endDate_current_year->format("Y-m-d 23:59:59") . "')";
			$WhereRaw = "ad_country_code = '" . $currentLocale . "'";
			//$AdsCaracts = AdsCaracts::select('id', 'status', 'created_at')->whereRaw($WhereRaw)->get();
			$AdsCaractsActive = AdsCaracts::select('id', 'status', 'created_at')->where('status', 'active')->whereRaw($WhereRaw)->get();
			$AdsCaractsInactive = AdsCaracts::select('id', 'status', 'created_at')->where('status', 'inactive')->whereRaw($WhereRaw)->get();
			$AdsCaractsRemoved = AdsCaracts::select('id', 'status', 'created_at')->where('status', 'removed')->whereRaw($WhereRaw)->get();
			$AdsCaractsInModeration = AdsCaracts::select('id', 'status', 'created_at')->where('status', 'in_moderation')->whereRaw($WhereRaw)->get();
			$AdsCaractsUnpublished = AdsCaracts::select('id', 'status', 'created_at')->where('status', 'unpublished')->whereRaw($WhereRaw)->get();

			$Total['ads'] = $AdsCaractsActive->count();

			$count['AdsCaractsActive'] = $AdsCaractsActive->count();
			$count['AdsCaractsInactive'] = $AdsCaractsInactive->count();
			$count['AdsCaractsRemoved'] = $AdsCaractsRemoved->count();
			$count['AdsCaractsInModeration'] = $AdsCaractsInModeration->count();
			$count['AdsCaractsUnpublished'] = $AdsCaractsUnpublished->count();
			$chartAdsCaractsByMonth = Charts::multiDatabase('line', 'chartjs')
			//$chartAdsCaractsByMonth = Charts::multiDatabase('bar', 'chartjs')
				->title("Ad's by month")
				->elementLabel("Total")
				//->stacked(true)
				//->responsive(true)
				->dataset('Total Active' . ' (' . $count['AdsCaractsActive'] . ')', $AdsCaractsActive)
				->dataset('Total Inactive' . ' (' . $count['AdsCaractsInactive'] . ')', $AdsCaractsInactive)
				->dataset('Total Removed' . ' (' . $count['AdsCaractsRemoved'] . ')', $AdsCaractsRemoved)
				->dataset('Total In Moderation' . ' (' . $count['AdsCaractsInModeration'] . ')', $AdsCaractsInModeration)
				->dataset('Total Unpublished' . ' (' . $count['AdsCaractsUnpublished'] . ')', $AdsCaractsUnpublished)
				->colors(['#8BC34A', '#FFC107', '#F44336', '#2196F3', '#607D8B', '#3295BD', '#A5488B', '#DCAD66'])
				// green, yellow, red, blue, blue grey, bleu grey green, purple, light brown
				//->groupByMonth($current_year, true);
				//->groupByDay();
				//->groupByMonth();
				->lastByMonth();
		}

		$viewName = app('request')->route()->getName();
		$viewName = str_replace('show','index',$viewName);
		$user_id = Auth::user()->id;
		$user = User::findOrFail($user_id);
		$user_type = $user->type;
		switch($user_type) {
			case 'admin':
				$usercaracts = [];
				break;
			case 'private':
				$usercaracts = PrivatesCaracts::where('user_id', $user_id)->get();
				break;
			case 'dealer':
				$usercaracts = DealersCaracts::where('user_id', $user_id)->get();
				break;
			case 'customer':
				$usercaracts = CustomersCaracts::where('user_id', $user_id)->get();
				break;
			case 'commercial':
				$usercaracts = CommercialsCaracts::where('user_id', $user_id)->get();
				break;
		}
		$array = json_decode(json_encode($usercaracts), true);
		//asort($array);
		if(!empty($array[0])) {
			$user_caracts = $array[0];
		}

		$countries = Countries::orderBy("name", "asc")->pluck('name','id')->all();
		$return = compact(
			'user', 'user_caracts', 'countries',

			'ad_id',
			'EffectiveUrl',
			'Total',
			'adEvents',
			'TotalAdStatsEvents',

			'chartGlobal',
			'chartGlobalPie',
			'chartGlobalEvents',
			'chartGlobalEventsPie',

			'chartAdsCaractsByMonth',

			'chartTotalVisitorsAndPageViews',

			'path',
			'chartTotalVisitorsAndPageViewsByPath',

			'chartContact',
			'chartEnquiryBOD',
			'chartBODsByMonth', 'chartBODsTotalPie', 'BODsTotal',
			'chartProspectiveCustomersByMonth', 'chartProspectiveCustomersPie'
		);
		return view($viewName, $return);
	}

	public static function adStatsEvents($ad_id='', $fullDimensions=true, $startDate = '', $endDate = '', $groupBy = 'yearMonth')
	{
		$_this = new self;
		$filterTarget = 'ga:eventLabel';

		$startDate = !empty($startDate) ? $startDate : $_this->startDate_current_year;
		$endDate = !empty($endDate) ? $endDate : $_this->endDate_current_year;

		if(empty($groupBy)) {
			$groupBy = 'yearMonth';
		}

		if (!empty($ad_id)) {
			$fullDimensions = false;
		}
		//var_dump('$fullDimensions');
		//var_dump($fullDimensions);

		//var_dump('$groupBy');
		//var_dump($groupBy);

		if($fullDimensions) {
			$dimensions = 'ga:eventAction,ga:eventLabel';
			$dimensions .= ',ga:pagePath';
			//$dimensions .= ($groupBy != 'yearMonth') ? ',ga:' . $groupBy : '';
			$dimensions .= !empty($groupBy) ? ',ga:' . $groupBy : '';
		} else {
			$dimensions = 'ga:eventAction';
		}
		//var_dump('$dimensions');
		//var_dump($dimensions);


		if (!empty($ad_id)) {
			$filterVal = $ad_id;
			$filters = $filterTarget . '=@' . $filterVal;
		}
		// ==	Exact match	%3D%3D	Aggregate metrics where the city is Irvine:
		// =filters=ga:city%3D%3DIrvine
		// =!=	Does not match	!%3D	Aggregate metrics where the city is not Irvine:
		// =filters=ga:city!%3DIrvine
		// ==@	Contains substring	%3D@	Aggregate metrics where the city contains York:
		// =filters=ga:city%3D@York
		// =!@	Does not contain substring	!@	Aggregate metrics where the city does not contain York:
		// =filters=ga:city!@York
		// ==~	Contains a match for the regular expression	%3D~	Aggregate metrics where the city starts with New:
		// =filters=ga:city%3D~%5ENew.*
		// =(%5E is the URL encoded from of the ^ character that anchors a pattern to the beginning of the string.)
		// =!~	Does not match regular expression	!~	Aggregate metrics where the city does not start with New:
		// =filters=ga:city!~%5ENew.*

		//$filters = '';
		if (isset($filters) && !empty($filters)) {
			$others = array('filters' => $filters, 'dimensions' => $dimensions);
		} else {
			$others = array('dimensions' => $dimensions);
		}

		//$metrics = 'ga:sessionsWithEvent,ga:totalEvents';
		$metrics = 'ga:totalEvents';

		$result = LaravelAnalytics::performQuery($startDate, $endDate, $metrics, $others);

		$datas = [];
		if (is_array($result->rows) && count($result->rows) > 0) {
			foreach ($result->rows as $row) {

				if($fullDimensions) {
					$ref = str_slug($row[0], '_');
					$ref = str_slug($row[0], '_');
					$label = str_replace('Ref. ', '', $row[1]);
					$k = $label;
					if(preg_match("/^(http|https):\/\//i", $k)) {
						$k = current(array_reverse(explode('/',$k)));
					} elseif(!preg_match("/^\//i", $k)){
						$k = str_slug($k, '_');
					}

					//if($groupBy != 'yearMonth') {
					if(!empty($groupBy)) {
						$date = $row[3];
						$url = $row[2];
						$val = $row[4];
					} else {
						$url = $row[2];
						$val = $row[3];
					}

					if(array_key_exists($ref, $datas) && array_key_exists( $k, $datas[$ref])) {
						/*
						if($groupBy != 'yearMonth') {
							$datas[$ref][$k]['created_at'] = $date;
						}
						$datas[$ref][$k]['Total'] += $val;
						$datas[$ref][$k]['Source'] = $label;
						$datas[$ref][$k]['Url'] = $url;
						*/
						if($groupBy == 'date') {
							$datas[$ref][$date] += $val;
						} elseif($groupBy != 'yearMonth') {
							$datas[$ref][$date]['Total'] += $val;
							$datas[$ref][$date]['Source'] = $label;
							$datas[$ref][$date]['Url'] = $url;
						} else {
							$datas[$ref][$k]['Total'] += $val;
							$datas[$ref][$k]['Source'] = $label;
							$datas[$ref][$k]['Url'] = $url;
						}
					} else {
						/*
						if($groupBy != 'yearMonth') {
							$datas[$ref][$k]['created_at'] = $date;
						}
						$datas[$ref][$k]['Total'] = $val;
						$datas[$ref][$k]['Source'] = $label;
						$datas[$ref][$k]['Url'] = $url;
						*/
						if($groupBy == 'date') {
							$datas[$ref][$date] = $val;
						} elseif($groupBy != 'yearMonth') {
							$datas[$ref][$date]['Total'] = $val;
							$datas[$ref][$date]['Source'] = $label;
							$datas[$ref][$date]['Url'] = $url;
						} else {
							$datas[$ref][$k]['Total'] = $val;
							$datas[$ref][$k]['Source'] = $label;
							$datas[$ref][$k]['Url'] = $url;
						}
					}
				} else {
					$datas[] = ['Action' => $row[0], 'Total' => $row[1]];
				}
			}
			//$result = new Collection($datas);
			//$return = $result->toArray();
			$return = new Collection($datas);
		} else {
			$return = [];
		}
		unset($result);
		return $return;
	}

	public static function chartTotalVisitorsAndPageViews($path='', $options=[]) {
		$_this = new self;
		$Analytics['LastWeek'] = [
			'groupBy' => 'date',
			'startDate' => $_this->startDate_last_week,
			'endDate' => $_this->endDate_last_week,
			'format' => $_this->formatDOW
		];
		$Analytics['CurrentWeek'] = [
			'groupBy' => 'date',
			'startDate' => $_this->startDate_current_week,
			'endDate' => $_this->endDate_current_week,
			'format' => $_this->formatDOW
		];
		$Analytics['LastMonth'] = [
			'groupBy' => 'date',
			'startDate' => $_this->startDate_last_month,
			'endDate' => $_this->endDate_last_month,
			'format' => $_this->formatDate
		];
		$Analytics['CurrentMonth'] = [
			'groupBy' => 'date',
			'startDate' => $_this->startDate_current_month,
			'endDate' => $_this->endDate_current_month,
			'format' => $_this->formatDate
		];
		$Analytics['LastYear'] = [
			'groupBy' => 'yearMonth',
			'startDate' => $_this->startDate_last_year,
			'endDate' => $_this->endDate_last_year,
			'format' => $_this->formatDateByMonth
		];
		$Analytics['CurrentYear'] = [
			'groupBy' => 'yearMonth',
			'startDate' => $_this->startDate_current_year,
			'endDate' => $_this->endDate_current_year,
			'format' => $_this->formatDateByMonth
		];

		$filters = '';
		if(isset($path) && !empty($path)) {
			$metrics = 'ga:visits,ga:pageviews';
			if(is_numeric($path) && array_key_exists('EffectiveUrl', $options)) {
				$filterTarget = 'ga:pagePath';
				$path = $options['EffectiveUrl'];
				$filters = $filterTarget . '=@' . $path;
			} else {
				$filterTarget = 'ga:pagePath';
				$filters = $filterTarget . '==' . $path;
			}
		}
		$chartTotalVisitorsAndPageViews = [];
		foreach ($Analytics as $k => $v) {
			$groupBy 	= $Analytics[$k]['groupBy'];
			$dimensions = 'ga:' . $groupBy;

			$startDate 	= $Analytics[$k]['startDate'];
			$endDate 	= $Analytics[$k]['endDate'];
			$format 	= $Analytics[$k]['format'];

			if(isset($path) && !empty($path)) {
				if (isset($filters) && !empty($filters)) {
					$others = array('filters' => $filters, 'dimensions' => $dimensions);
				} else {
					$others = array('dimensions' => $dimensions);
				}

				$result = LaravelAnalytics::performQuery($startDate, $endDate, $metrics, $others);

				foreach ($result->rows as $row) {
					$datas[] = [$groupBy => Carbon::createFromFormat(($groupBy == 'yearMonth' ? 'Ym' : 'Ymd'), $row[0]), 'visitors' => $row[1], 'pageViews' => $row[2]];
				}
				$analyticsDatas = new Collection($datas);
				unset($datas);
			} else {
				$analyticsDatas = LaravelAnalytics::getVisitorsAndPageViewsForPeriod($startDate, $endDate, $groupBy);
			}

			$TotalVisitorsAndPageViews = $analyticsDatas;
			$TotalVisitorsAndPageViewsDates = $TotalVisitorsAndPageViews->lists($groupBy)->toArray();
			$TotalVisitorsAndPageViewsVisitors = $TotalVisitorsAndPageViews->lists('visitors')->toArray();
			$TotalVisitorsAndPageViewsPageViews = $TotalVisitorsAndPageViews->lists('pageViews')->toArray();
			if(
				isset($TotalVisitorsAndPageViewsVisitors) && !empty($TotalVisitorsAndPageViewsVisitors) ||
				isset($TotalVisitorsAndPageViewsPageViews) && !empty($TotalVisitorsAndPageViewsPageViews)
			) {
				$TotalVisitorsAndPageViewsDates = array_map($format, $TotalVisitorsAndPageViewsDates);

				$title = ucfirst(preg_replace('/(?<!^)[A-Z]/', ' $0', $k)) .
					" Total Visitors" . ' (' . array_sum($TotalVisitorsAndPageViewsVisitors) . ')' .
					" And PageViews" . ' (' . array_sum($TotalVisitorsAndPageViewsPageViews) . ')';
				if(isset($path) && !empty($path)) {
					$title = '[ ' . 'V:' . array_sum($TotalVisitorsAndPageViewsVisitors) . ' ] ';
					$title .= '[ ' . 'Pv:' . array_sum($TotalVisitorsAndPageViewsPageViews) . ' ] ';
					$title .= ucfirst(trans('navigation.for')) . ' : ' . $path;
				}
				$chart = Charts::multi('mix', 'chartjs')
				//$chart = Charts::multi('bar', 'chartjs')
				//$chart = Charts::multi('line', 'chartjs')
					->title($title)
					->elementLabel("Total")
					->responsive(true)
					->dataset('Total Visitors', $TotalVisitorsAndPageViewsVisitors, ['type'=>'bar', 'fill'=>'true', 'total'=>array_sum($TotalVisitorsAndPageViewsVisitors)])
					->dataset('Total PageViews', $TotalVisitorsAndPageViewsPageViews, ['type'=>'line', 'fill'=>'false', 'total'=>array_sum($TotalVisitorsAndPageViewsPageViews)])
					->labels($TotalVisitorsAndPageViewsDates);

				if(preg_match('/^Last/', $k)) {
					$chart->colors(['#3295BD', '#A5488B', '##DCAD66', '#3295BD']);
				} else {
					$chart->colors(['#2196F3', '#8BC34A', '#FFC107', '#F44336']);
				}
				if(array_sum($TotalVisitorsAndPageViewsVisitors) > 0 || array_sum($TotalVisitorsAndPageViewsPageViews) > 0) {
					$chartTotalVisitorsAndPageViews[$k] = $chart;
				}
			}
		}
		return $chartTotalVisitorsAndPageViews;
	}

	public function show (Request $request) {
		return $this->index($request);
	}

}