<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;
use App\Models\Tutorial;
use File;

class FaqController extends Controller
{

    public function AddFaq()
    {
        return view('admin.faq.create_faq');
    }

    public function index()
    {
        $faqs = Faq::all();
        return view('newFaq', compact('faqs'));
    }

    public function CreateFaq(Request $request)
    {
        $question = new Faq;
        $question->category = $request->category;
        $question->question = $request->question;
        $question->response = $request->response;

        $question->save();

        return redirect()->route('admin.faq.ListFaq');
    }

    public function ListFaq()
    {
        $faqs = Faq::paginate(10);

        return view('admin.faq.list_faq', compact('faqs'));
    }

    public function FaqEdit($id)
    {
        $question_edit = Faq::find($id);

        return view('admin.faq.edit_faq', compact('question_edit'));
    }

    public function FaqUpdate(Request $request, $id)
    {
        $question = Faq::find($id);
        $question->update([
            'question' => $request->question,
            'category' => $request->category,
            'response' => $request->response,
        ]);

        return redirect()->route('admin.faq.ListFaq');
    }

    public function FaqDelete($id)
    {
        Faq::findOrFail($id)->delete();

        return redirect()->route('admin.faq.ListFaq');
    }



    public function CreateTutorial(Request $request)
    {
        $tutorial = new Tutorial;
        $tutorial->title = $request->title;
        $tutorial->category = 0;

        if ($request->hasFile('video') && $request->file('video')->isValid()) {

            $requestImage = $request->video;
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName()) . strtotime("now") . "." . $extension;
            $requestImage->move(public_path('videos/tutorials'), $imageName);

            $tutorial->video = $imageName;
        }

        $tutorial->save();

        return redirect()->route('admin.tutorial.ListTutorial');
    }

    public function ListTutorial()
    {
        $tutorials = Tutorial::paginate(9);

        return view('admin.tutorial.list_tutorial', compact('tutorials'));
    }

    public function TutorialEdit($id)
    {
        $tutorial = Tutorial::find($id);

        return view('admin.tutorial.edit_tutorial', compact('tutorial'));
    }

    public function TutorialUpdate(Request $request, $id)
    {
        $tutorial = Tutorial::find($id);
        $tutorial->update([
            'title' => $request->title,
        ]);

        if ($request->hasFile('video') && $request->file('video')->isValid()) {

            $filePath = public_path('videos/tutorials/' . $tutorial->video);

            if (file_exists($filePath)) {
                File::delete($filePath);
            }

            $requestImage = $request->video;
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName()) . strtotime("now") . "." . $extension;
            $requestImage->move(public_path('videos/tutorials'), $imageName);

            $tutorial->update([
                'title' => $imageName,
            ]);
        }

        return redirect()->route('admin.tutorial.ListTutorial');
    }

    public function TutorialDelete($id)
    {
        $tutorial = Tutorial::find($id);
        $filePath = public_path('videos/tutorials/' . $tutorial->video);

        if (file_exists($filePath)) {
            File::delete($filePath);
        }

        $tutorial->delete();

        return back();
    }

    public function PageTutorials()
    {
        $tutorials = Tutorial::paginate(9);

        return view('tutorials', compact('tutorials'));
    }

    public function PageTutorialsVideo($id)
    {
        $video = Tutorial::find($id);

        $tutorials = Tutorial::paginate(9);

        return view('tutorials', compact('tutorials', 'video'));
    }

    public function AddTutorial()
    {
        return view('admin.tutorial.create_tutorial');
    }
}
