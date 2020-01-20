<?php

namespace App\Http\Controllers\Client;

use App\Models\Suggest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SuggestRequest;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class SuggestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function suggestGet()
    {
        return view('client.suggest.index');
    }

    /**
     * @param SuggestRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function suggestPost(SuggestRequest $request)
    {
        try {
            $storaPath = storage_path(config('custome.path_storage_suggest'));
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            Suggest::create([
                'user_id' => auth()->user()->id,
                'image' => $imageName,
                'content' => $request->content,
            ]);
            request()->image->move($storaPath, $imageName);
            $fileReturn = $storaPath . $imageName;

            return redirect()->back()
                ->with(['suggestSuccess' => trans('custome.suggest_success')]);
        } catch (FileException $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}