<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Usage\UsageIndexCollection;
use App\Models\Usage;

class UsageApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $usages = Usage::orderBy('id', 'desc')
            ->with('user')->paginate(10);

        return new UsageIndexCollection($usages);

    }
}
