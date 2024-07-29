<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use Illuminate\Http\Request;
use App\Models\NewsletterEcomm;

class NewletterController extends Controller
{
    public function List()
    {
        $newsletter = NewsletterEcomm::all();

        return view('admin.members.newsletter', compact('newsletter'));
    }

    public function NewsletterRegister(Request $request)
    {
        $exists = NewsletterEcomm::where('email', $request->email)->first();

        if (!isset($exists)) {
            $news = new NewsletterEcomm;
            $news->name    = $request->name;
            $news->surname = $request->surname;
            $news->email   = $request->email;

            $news->save();
        }
        
        return back();
    }

    public function DeleteEmailNewsletter($id)
    {
        NewsletterEcomm::find($id)->delete();

        return back();
    }
}
