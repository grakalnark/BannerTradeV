<?php

namespace App\Http\Controllers;

use App\Models\LiveModel;
use Illuminate\Http\Request;

use App\Models\Event;
use Illuminate\Support\Facades\Events;

use App\Models\Lives;
use Illuminate\Support\Facades\Live;

class EventController extends Controller
{
    public function index($id){

        $data = date('d/m');
        $lives = LiveModel::all();

        $events = Event::whereDate('created_at',date('Y-m-d'))
            ->where('id_live', $id)
            ->get();


        return view('banner' , [
            'events' => $events,
            'data' => $data,
            'lives'=> $lives,
            'id_live'=> $id
            ]);

    }

    public function createLive(){

        return view('addlives.lives');

    }

    public function storeLive(Request $request){

        /*$live = new Lives;

        $live->nomelive = $request->nomelive;

        $live->save();

        return redirect('/addativos/create');*/

    }

    public function create($id){



        return view('addativos.create',
            [
                'id_live' => $id]);

    }

    public function store(Request $request, $id){

        $data = $request->all();

        Event::create([
            'nomeativo'=> $data['nomeativo'],
            'minutagem' => $data[ 'minutagem'],
            'id_live' => $id,
        ]);

        return view('addativos.create', [
            'id_live' => $id
        ]);
    }

    public function inicio(Request $request){

        $data = $request->all();
        $lives = LiveModel::all();

        return view('welcome', [
            'id_live' => $data['id_live'],
            'lives'=> $lives
        ]);

    }

    public function indexEdit($id)
    {
        $events = Event::where('id_live', $id)->get();
        $lives = LiveModel::all();


        return view('edit' , ['events' => $events, 'id_live'=>$id, 'lives'=> $lives]);
    }

    public function update(Request $request,  $id, $idlive){

        $data = $request->all();
        $lives = LiveModel::all();

        $minutagem = $data['minutagem'];
        $nome = $data['nomeativo'];

        if ($minutagem != null){
            Event::where('id' , $id)->update(['minutagem' => $minutagem]);
        }
        if ($nome != null){
            Event::where('id' , $id)->update(['nomeativo' => $nome]);
        }

        return redirect('/edit-ativos', ['id_live'=>$idlive, 'lives'=> $lives]);
    }

    public function delete($id){

        Event::where('id' , $id)->delete();

        return redirect('/edit-ativos');
    }

    public function live($id){

        $data = date('d/m');

        $events = Event::whereDate('created_at',date('Y-m-d'))
            ->where('id_live', $id)
            ->get();

        $total = [];


        foreach($events as $item){
            $total[] = $item->nomeativo." - ".$item->minutagem;

        }

        $palavra = $total;
        $string = implode( "     |     ", $palavra);

        $cont = count($events);


        return view('bannerlive' , [
        'events' => $events,
        'palavra' => $string,
        'tam' => $cont
        ]);

    }

    public function busca(Request $request , $id){

        $data = $request->all();
        $lives = LiveModel::all();


        $events = Event::whereDate('created_at', $data['busca'])->get();

        $pesquisa = $data['busca'];

        $item = date("d/m" , strtotime($pesquisa));

        return view('banner' , [
            'events' => $events,
            'data' => $item,
            'lives' => $lives,
            'id_live' => $id
        ]);

    }

    public function conta(){
        return view('conta');

    }

    public function buscaedit(Request $request, $id){

        $data = $request->all();
        $events = Event::whereDate('created_at', $data['buscaedit'])
            ->where('id_live', $id)
            ->get();

        $pesquisaedit = $data['buscaedit'];

        $lives = LiveModel::all();

        $item = date("d/m/y" , strtotime($pesquisaedit));

        return view('edit' , [
            'events' => $events,
            'data' => $item,
            'id_live' => $id,
            'lives' => $lives
        ]);


    }

}
