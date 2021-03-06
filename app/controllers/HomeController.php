<?php

use GuzzleHttp\Client;

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showWelcome()
	{
		return View::make('hello');
	}

    public function getIndex()
    {
        $nades = Nade::where('approved_at', '>', '2014-10-13')
                        ->orderBy('approved_at', 'desc')
                        ->orderBy('created_at', 'desc')
                        ->orderBy('id', 'desc')
                        ->limit(10)
                        ->get();

        $viewData = array(
            'nades' => $nades,
        );
        
        return View::make('home.home')->with($viewData);
    }

    public function showDonations()
    {
        $viewData = array(
            'heading' => 'Donations',
        );
        return View::make('home.donations')->with($viewData);
    }

    public function showFeatures()
    {
        $endpoint = "https://gitlab.com/api/v3/projects/104982/issues?labels=Feature&state=opened";
        $options  = array('headers' => array('PRIVATE-TOKEN' => getenv('gitlab_api_key')));
        $client   = new Client();
        $features = $client->get($endpoint, $options)->json();
        $viewData = array(
            'heading'  => 'Requested Features',
            'features' => $features,
        );

        return View::make('home.features')->with($viewData);
    }
}
