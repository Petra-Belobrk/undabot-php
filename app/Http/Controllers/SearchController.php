<?php

namespace App\Http\Controllers;

use App\Keyword;
use App\Provider;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $providers = Provider::pluck('name', 'id')->all();
        return view('welcome', compact('providers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($data)
    {
        $sucks = $data[0]['sucks'];
        $rocks = $data[0]['rocks'];
        $total = $rocks + $sucks;
        $keyword = Keyword::create(['name' => $data[0]['term']['search'],
                    'providerId' => $data[0]['term']['provider'],
                    'count_all' => $total,
                    'rocks' => $rocks,
                    'sucks' => $sucks]);
        if(isset($data[0]['term']['apiv2'])) {
            return $this->apiV2($keyword);
        } else {
            echo response()->json([
                "term" =>$keyword->name,
                "score" => $keyword->score(),
            ]);
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function search(Request $request) {
        $input = $request->all();
        $search = $input['search'];
            $providersId = $input['provider'];
            //checking db for existing terms
            $term = Keyword::query()->where([
                ['name', '=', $search],
                ['providerId', '=', $providersId],
            ])->get();
//if term exist in db, echoes json response, if not, term is sent to api method
            if(count($term) > 0 && !isset($input['apiv2'])) {
                echo response()->json([
                    "term" => $term[0]->name,
                    "score" => $term[0]->score(),
                ]);
            } elseif(count($term) == 0) {
                return $this->api($input);
            } elseif (count($term) > 0 && $input['apiv2']) {
                return $this->apiV2($term[0]);
            }



    }

    //method to call on provider url

    public function api($term)
    {
        // Init Guzzle client
        $client = new Client();
        $name = $term['search'];
        $urlId = Provider::findOrFail($term['provider']);
        $url = $urlId->url;

        // Api calls on provider url
        $sucksApi = json_decode($client->request('GET', "$url?q='$name%20sucks'")->getBody()->getContents());
        $rocksApi = json_decode($client->request('GET', "$url?q='$name%20rocks'")->getBody()->getContents());

        $sucks = $sucksApi->total_count;
        $rocks = $rocksApi->total_count;

        //sending data to store method
        $data[] = ['term' => $term, 'sucks' => $sucks, 'rocks' => $rocks];
        return $this->store($data);

    }

    //method for second version of returned json format

    public function apiV2($keyword) {

       echo response()->json([
           'links' => [
               'self' => "http://www.undabot.test/search/$keyword->name"
           ],
            'data' => [
                'type' => "keyword",
                'id' => $keyword->id,
                'attributes' => [
                    'title' => $keyword->name,
                    'score' => $keyword->score(),
                    'positive_terms' => $keyword->rocks,
                    'negative_terms' => $keyword->sucks,
                    'total_number' => $keyword->count_all,
                ],
                'relationships' => [
                    'provider' => $keyword->provider->name,
                    'url' => $keyword->provider->url,
                ],

            ]
        ]);

    }

}
