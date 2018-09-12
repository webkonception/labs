<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\ContactFormRequest;

use App\ContactForm;
use Mail;
use Route;

class ContactController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function create()
    {
        return view('contact');
    }

    /**
     * Store a newly created contact in storage.
     *
     * @param ContactFormRequest|Request $request
     */
    public function store(ContactFormRequest $request)
    {
        try {
            $datasRequest = $request->all();
            $country_code = !empty($datasRequest['country_code']) ? $datasRequest['country_code'] : 'uk';
            $contact = ContactForm::create($datasRequest);

            if ($contact->save()) {
                $datasEmail = array(
                    'website_name' => config('youboat.' . $country_code . '.website_name'),
                    'type_request' => !empty($datasRequest['type_request']) ? trans('boatgest.type_request_contact', ['type_request'=>$datasRequest['type_request']]) : 'a contact request',
                    'name' => !empty($datasRequest['name']) ? mb_strtoupper($datasRequest['name']) : null,
                    'email' => !empty($datasRequest['email']) ? $datasRequest['email'] : null,
                    'phone' => !empty($datasRequest['phone']) ? $datasRequest['phone'] : null,
                    'user_message' => !empty($datasRequest['message']) ? $datasRequest['message'] : null,
                    'country_code' => $country_code,
                    'bcc_mails' => config('youboat.' . $country_code . '.emails_bcc'),
                    'contact_email' => config('youboat.' . $country_code . '.contact_email'),
                    'MAIL_NO_REPLY_EMAIL' => config('youboat.' . $country_code . '.MAIL_NO_REPLY_EMAIL'),
                    'MAIL_NO_REPLY_NAME' => config('youboat.' . $country_code . '.MAIL_NO_REPLY_NAME')
                );

                Mail::send('emails.contact', $datasEmail, function ($message) use ($datasEmail) {
                    $message->subject(trans('emails.welcome_to') . ' ' . $datasEmail['website_name']);
                    $message->from($datasEmail['MAIL_NO_REPLY_EMAIL'], trans('navigation.contact') . ' ' . trans('emails.from') . ' ' . $datasEmail['website_name']);
                    $message->replyTo($datasEmail['MAIL_NO_REPLY_EMAIL'], $datasEmail['MAIL_NO_REPLY_NAME'] . ' ' . $datasEmail['website_name']);
                    $message->subject("Welcome to " . env('APP_NAME'));
                    $message->to($datasEmail['email'], $datasEmail['name']);
                });

                Mail::send('emails.get_notified', $datasEmail, function ($message) use ($datasEmail) {
                    $message->subject($datasEmail['website_name'] . " > " . $datasEmail['type_request'] . ' ' . trans('emails.from') . ' ' . $datasEmail['email']);
                    $message->from($datasEmail['MAIL_NO_REPLY_EMAIL'], trans('navigation.contact') . ' ' . trans('emails.from') . ' ' . $datasEmail['website_name']);
                    $message->replyTo($datasEmail['email'], $datasEmail['name']);
                    $message->bcc($datasEmail['bcc_mails'],$datasEmail['bcc_mails']);
                    $message->to($datasEmail['contact_email'],$datasEmail['contact_email']);
                });

                $msg = 'Thanks for contacting us!';
                $request->session()->put('contact_message.text', $msg);
                $request->session()->put('contact_message.type', 'success');

                //return redirect()->route('contact')->with('contact_message', $msg);
                $currentRoute = $request->route()->name();
                if($currentRoute !== 'contact') {
                    $route = preg_replace('/contact_', '', $currentRoute);
                    return redirect()->route($route);
                } else {
                    return redirect()->route('contact');
                }
            }
        } catch(\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
            //return redirect()->route('contact')->withErrors($e->getMessage());
        }
    }
}
