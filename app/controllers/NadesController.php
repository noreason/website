<?php

class NadesController extends BaseController {

    public function addNade()
    {
        //
    }

    public function saveNade($id = null)
    {
        if ($id) {
            $nade = Nade::find($id);
        } else {
            $nade = new Nade();
        }

        $map  = Map::find(Input::get('map'));
        $user = Auth::user();

        $nade->map()->associate($map);
        $nade->user()->associate($user);

        $nade->type        = Input::get('type');
        $nade->pop_spot    = Input::get('pop_spot');
        $nade->title       = Input::get('title');
        $nade->imgur_album = Input::get('imgur_album');
        $nade->youtube     = Input::get('youtube');
        $nade->tags        = Input::get('tags');
        $nade->is_working  = Input::get('is_working');
        $nade->is_approved = false;

        if (Auth::user()->is_mod) {
            $nade->is_approved = Input::get('is_approved');
        }

        if ($nade->save()) {
            Session::flash('flashSuccess', 'The nade has been saved!');
            Redirect::action('NadesController@showNadeForm');
        }

        $viewData = array(
            'heading'   => 'Add a Nade',
            // 'maps'      => Map::all()->sortBy('name'),
            'nadeTypes' => Nade::getNadeTypes(),
            'popSpots'  => Nade::getPopSpots(),
        );

        Session::flash('flashError', 'The nade was not saved!');
        return View::make('nades.nade-form')->with($viewData);
    }

    public function deleteNade()
    {
        # code...
    }

    public function showNadesInMap(Map $map)
    {
        return $map;
    }

    public function showMapAtPopSpot(Map $map, $pop)
    {
        return $map . " | " . $pop;
    }

    public function showNadeForm()
    {
        // return "lawl nades";

        // $nadeArr = array(
        //     'type'        => 'flash',
        //     'pop_spot'    => 'b-site',
        //     'title'       => 'bewbs',
        //     'imgur_album' => 'linky',
        //     'is_working'  => true,
        //     'tags'        => 'short'
        // );
        // $nade = new Nade($nadeArr);
        // $map  = Map::find(1);
        // $user = Auth::user();

        // $nade->map()->associate($map);
        // $nade->user()->associate($user);

        // if ($nade->save()) {
        //     return "saved";
        // }
        
        $viewData = array(
            'heading'   => 'Add a Nade',
            // 'maps'      => Map::all()->sortBy('name'),
            'nadeTypes' => Nade::getNadeTypes(),
            'popSpots'  => Nade::getPopSpots(),
        );

        return View::make('nades.nade-form')->with($viewData);
    }
}
