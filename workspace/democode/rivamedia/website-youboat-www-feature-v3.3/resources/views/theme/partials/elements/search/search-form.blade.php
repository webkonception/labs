<!-- Search Form -->
<div class="search-form">
    <div class="search-form-inner">
        <form>
            <h3>{!! trans('search.search_form_title') !!}</h3>
            <div class="row">
                {!! Form::open(array('url'=>trans_route($currentLocale, 'routes.for_sale'), 'role'=> '', 'id'=>'search_form_inner', 'autocomplete'=>'off')) !!}
                {!! csrf_field() !!}
                {!! Form::hidden('country_code', $country_code) !!}
                <div class="col-md-6 col-sm-6">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Postcode</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Body Type</label>
                            <select name="Body Type" class="form-control selectpicker">
                                <option selected>Any</option>
                                <option>Wagon</option>
                                <option>Minivan</option>
                                <option>Coupe</option>
                                <option>Crossover</option>
                                <option>Van</option>
                                <option>SUV</option>
                                <option>Minicar</option>
                                <option>Sedan</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Make</label>
                            <select name="Make" class="form-control selectpicker">
                                <option selected>Any</option>
                                <option>Jaguar</option>
                                <option>BMW</option>
                                <option>Mercedes</option>
                                <option>Porsche</option>
                                <option>Nissan</option>
                                <option>Mazda</option>
                                <option>Acura</option>
                                <option>Audi</option>
                                <option>Bugatti</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Model</label>
                            <select name="Model" class="form-control selectpicker">
                                <option selected>Any</option>
                                <option>GTX</option>
                                <option>GTR</option>
                                <option>GTS</option>
                                <option>RLX</option>
                                <option>M6</option>
                                <option>S Class</option>
                                <option>C Class</option>
                                <option>B Class</option>
                                <option>A Class</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Price Min</label>
                            <select name="Min Price" class="form-control selectpicker">
                                <option selected>Any</option>
                                <option>$10000</option>
                                <option>$20000</option>
                                <option>$30000</option>
                                <option>$40000</option>
                                <option>$50000</option>
                                <option>$60000</option>
                                <option>$70000</option>
                                <option>$80000</option>
                                <option>$90000</option>
                                <option>$100000</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Price Max</label>
                            <select name="Max Price" class="form-control selectpicker">
                                <option selected>Any</option>
                                <option>$10000</option>
                                <option>$20000</option>
                                <option>$30000</option>
                                <option>$40000</option>
                                <option>$50000</option>
                                <option>$60000</option>
                                <option>$70000</option>
                                <option>$80000</option>
                                <option>$90000</option>
                                <option>$100000</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="checkbox-inline">
                                <input type="checkbox" id="inlineCheckbox1" value="option1"> Brand new only
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" id="inlineCheckbox2" value="option2"> Certified
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Min Year</label>
                            <select name="Min Year" class="form-control selectpicker">
                                <option selected>Any</option>
                                <option>2005</option>
                                <option>2006</option>
                                <option>2007</option>
                                <option>2008</option>
                                <option>2009</option>
                                <option>2010</option>
                                <option>2011</option>
                                <option>2012</option>
                                <option>2013</option>
                                <option>2014</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Max Year</label>
                            <select name="Max Year" class="form-control selectpicker">
                                <option selected>Any</option>
                                <option>2005</option>
                                <option>2006</option>
                                <option>2007</option>
                                <option>2008</option>
                                <option>2009</option>
                                <option>2010</option>
                                <option>2011</option>
                                <option>2012</option>
                                <option>2013</option>
                                <option>2014</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Min Mileage</label>
                            <select name="Min Mileage" class="form-control selectpicker">
                                <option selected>Any</option>
                                <option>10000</option>
                                <option>20000</option>
                                <option>30000</option>
                                <option>40000</option>
                                <option>50000</option>
                                <option>60000</option>
                                <option>70000</option>
                                <option>80000</option>
                                <option>90000</option>
                                <option>100000</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Max Mileage</label>
                            <select name="Max Mileage" class="form-control selectpicker">
                                <option selected>Any</option>
                                <option>10000</option>
                                <option>20000</option>
                                <option>30000</option>
                                <option>40000</option>
                                <option>50000</option>
                                <option>60000</option>
                                <option>70000</option>
                                <option>80000</option>
                                <option>90000</option>
                                <option>100000</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Transmission</label>
                            <select name="Transmission" class="form-control selectpicker">
                                <option selected>Any</option>
                                <option>5 Speed Manual</option>
                                <option>5 Speed Automatic</option>
                                <option>6 Speed Manual</option>
                                <option>6 Speed Automatic</option>
                                <option>7 Speed Manual</option>
                                <option>7 Speed Automatic</option>
                                <option>8 Speed Manual</option>
                                <option>8 Speed Automatic</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Body Color</label>
                            <select name="Body Color" class="form-control selectpicker">
                                <option selected>Any</option>
                                <option>Red</option>
                                <option>Black</option>
                                <option>White</option>
                                <option>Yellow</option>
                                <option>Brown</option>
                                <option>Grey</option>
                                <option>Silver</option>
                                <option>Gold</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <input type="submit" class="btn btn-block btn-info btn-lg" value="Find my vehicle now">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>