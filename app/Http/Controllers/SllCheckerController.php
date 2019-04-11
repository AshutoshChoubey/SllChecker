<?php

namespace App\Http\Controllers;

use App\SllChecker;
use Illuminate\Http\Request;
use App\Http\Requests\SllCheckerRequest;
use Spatie\SslCertificate\SslCertificate;
use DateTime;

class SllCheckerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sllChecker = SllChecker::latest()->paginate(15);
        $viewData['sllChecker'] =  $sllChecker;
        return view('sllchecker_view', $viewData);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SllCheckerRequest $request)
    {
            $SllChecker = SllChecker::create($request->all());
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'Domain Added Successfully!');
            return redirect()->route('index');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SllChecker  $sllChecker
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        SllChecker::where('id',$request->id)->forceDelete();
          // SllChecker::destroy();//this is soft delete for SllChecker
    }
    public function sslChecker(Request $request)
    {
        $DomainDetail=SllChecker::whereId($request->id)->first()->toArray();
        $certificate = SslCertificate::createForHostName($DomainDetail['domain_name']);
        $ssl_issuer = $certificate->getIssuer(); // returns "Let's Encrypt Authority X3"
        $isValid=$certificate->isValid(); // returns true if the certificate is currently valid
        $ssl_expiry= $certificate->expirationDate(); // returns an instance of Carbon
        $sllCheckerForUpdate = SllChecker::findOrFail($request->id);
        $date = new DateTime( $ssl_expiry);
        $ssl_expiry = $date->format('Y-m-d H:i:s');
        if($isValid==1)
        {
            if($sllCheckerForUpdate->update(['ssl_issuer'=> $ssl_issuer,'ssl_expiry'=> $ssl_expiry]))
            {
                 return  json_decode(json_encode($sllCheckerForUpdate), true);
            }
            else
            {
                 return response()->json('error');
            } 
        }
        else
        {
            return response()->json('error');
        }
       
    }
   
    public function refreshAfterFail(Request $request)
    {
        $sllCheckerForUpdate = SllChecker::findOrFail($request->id);
        if($sllCheckerForUpdate->update(['ssl_issuer'=> 'invalid','ssl_expiry'=> 'invalid']))
        {
             return response()->json($sllCheckerForUpdate);
        }
        else
        {
             return response()->json('error');
        } 
    }
}
