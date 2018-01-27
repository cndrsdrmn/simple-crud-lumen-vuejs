<?php

namespace App\Http\Controllers;

use App\Example;
use Illuminate\Http\Request;

class ExampleController extends Controller
{
    /**
     * [$example description]
     * 
     * @var [type]
     */
    protected $example;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Example $example)
    {
        $this->example = $example;
    }

    /**
     * [index description]
     * 
     * @return [type] [description]
     */
    public function index()
    {
        return view('index');
    }

    /**
     * [fetch description]
     * 
     * @return [type] [description]
     */
    public function fetch(Request $request)
    {
        $sort = explode('|', $request->get('sort', 'id|asc'));
        $examples = $this->example->search($request)
                         ->orderBy($sort[0], $sort[1])
                         ->paginate($request->get('per_page', 10));

        return response()->json($examples);
    }

    /**
     * [store description]
     * 
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->example->getRules());
        $example = $this->example->create($request->except(['id']));
        return response()->json(['message' => 'Success created data.'], 201);
    }

    /**
     * [edit description]
     * 
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        $example = $this->example->find($id);
        return response()->json($example);
    }

    /**
     * [update description]
     * 
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, $this->example->getRules());
        $example = $this->example->find($id);
        $example->update($request->except(['id']));
        return response()->json(['message' => 'Success updated data.'], 200);
    }

    /**
     * [destroy description]
     * 
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy($id)
    {
        $example = $this->example->find($id)->delete();
        return response()->json(['message' => 'Success deleted data.'], 200);
    }
}
