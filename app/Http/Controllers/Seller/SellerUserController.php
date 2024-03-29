<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use Auth,Input,URL;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Repository\UserInterface;

class SellerUserController extends Controller
{
    public $userRepo;
    //public $userType;
    public function __construct(UserInterface $userRepo){
        $this->userRepo = $userRepo;
        $this->userRepo->companyId = session('company_id');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->userRepo->keywords = $keyword = Input::get('search_box');
        $this->userRepo->userType = $user_type = array(roleId('Seller'),roleId('Both'));
        $this->userRepo->status = $status = Input::get('status');
        $users = $this->userRepo->getUsers();
        $users->setPath(URL::route('seller.user.view'));
        return view('seller.user.userListing',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->userRepo->userType = roleId('Seller');
        $roles = $this->userRepo->getRoles();
        return view('seller.user.manageUser',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->userRepo->userType=roleId('Seller');
        $data = $this->userRepo->save($request);
        echo json_encode($data);
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
        $data = $this->userRepo->save($request);
        echo json_encode($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        session(['uuid' => $id]);
        $this->userRepo->uuid = $id; 
        $user = $this->userRepo->getUsers();
        $this->userRepo->userType = roleId('Seller');
        $roles = $this->userRepo->getRoles();
        return view('seller.user.manageUser',compact('user','roles'));
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
}
