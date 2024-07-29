<?php

namespace App\Http\Controllers\Admin;

use App\Models\HistoricScore as Score;
use App\Http\Controllers\Controller;
use App\Models\Career;
use App\Models\Popup;
use App\Models\Landing;
use App\Models\SystemConf;
use App\Traits\CustomLogTrait;
use Illuminate\Http\Request;
use Storage;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;



class SettingsAdminController extends Controller
{
   use CustomLogTrait;
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function mlmLevel()
   {
      $scores = Career::all();
      return view('admin.settings.mlmLevel', compact('scores'));
   }

   /**
    * Show the form for editing the specified resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function edit($id)
   {
      $scores = Career::find($id);
      return view('admin.settings.edit', compact('scores'));
   }

   public function update(Request $request, $id)
   {

      $data = $request->only([
         'score',
         'bonus',
      ]);

      $scores = Career::find($id);
      $scores->update($data);

      return redirect()->route('admin.settings.mlmLevel')->with('success', 'Your data has been successfully updated!');
   }

   public function popup()
   {
      $popup = Popup::all();
      return view('admin.settings.popup', compact('popup'));
   }

   /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function inactive($id)
    {
        try {
            $popup = Popup::find($id);
            $popup->activated = false;

            $popup->update();
            $this->createLog('Popup inactive successfully', 204, 'success', auth()->user()->id);
            flash(__('admin_alert.wlinactive'))->success();
            return redirect()->route('admin.settings.popup');
        } catch (Exception $e) {
            $this->errorCatch($e->getMessage(), auth()->user()->id);
            flash(__('admin_alert.wlnotremove'))->error();
            return redirect()->back();
        }
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activated($id)
    {
        try {
            $popup = Popup::find($id);
            $popup->activated = true;

            $popup->update();
            $this->createLog('Popup activated successfully', 204, 'success', auth()->user()->id);
            flash(__('admin_alert.wlactivated'))->success();
            return redirect()->route('admin.settings.popup');
        } catch (Exception $e) {
            $this->errorCatch($e->getMessage(), auth()->user()->id);
            flash(__('admin_alert.wlnotactivated'))->error();
            return redirect()->back();
        }
    }

    public function editpop($id)
    {
       $popup = Landing::find($id);
       return view('admin.settings.editpop', compact('popup'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatepop(Request $request, $id)
    {
        try {
            $data = $request->only([
                'title',
            ]);

            $popup = Popup::find($id);


            if ($request->hasFile('path')) {
                $images = $request->file('path')->store('admin/popup', 'public');
                $data['path'] = $images;
            }

            $popup->update($data);

            $this->createLog('Popup updated successfully', 200, 'success', auth()->user()->id);
            flash(__('admin_alert.pkgupdate'))->success();
            return redirect()->route('admin.settings.popup');
        } catch (Exception $e) {
            $this->errorCatch($e->getMessage(), auth()->user()->id);
            flash(__('admin_alert.pkgnotupdate'))->error();
            return redirect()->back();
        }
    }

   // Store Image Popup
   public function storeImage(Request $request)
   {
      $this->validate($request, [
         'title' => 'required|string|max:255',
         'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
     ]);

     $image_path = $request->file('image')->store('image', 'public');

     $data = Popup::create([
         'title' => $request->title,
         'path' => $image_path,
         'activated' => NULL,
     ]);

     session()->flash('success', 'Image Upload successfully');

     return redirect()->route('admin.settings.popup');
   }

   public function indication()
   {
      $indication = Landing::all();
      return view('admin.settings.indication', compact('indication'));
   }

   public function editVideo($id)
   {
      $videos = Landing::find($id);
      return view('admin.settings.editVideo', compact('videos'));
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function create()
   {
      return view('admin.settings.create');
   }


   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request)
   {
      $data = $request->only([
         'description',
         'img_url',
      ]);

      $videos = Landing::create($data);

      flash(__('admin_alert.videoupload'))->success();
      return redirect()->route('admin.settings.indication');
   }

   public function updateVideo(Request $request, $id)
   {
      $data = $request->only([
         'description',
         'img_url',
      ]);

      $videos = Landing::find($id);
      $videos->update($data);

      flash(__('admin_alert.videoupdate'))->success();
      return redirect()->route('admin.settings.indication');
   }
   public function systemuser()
   {
      $system = SystemConf::first();
      return view('admin.users.system', compact('system'));
   }

   public function upsystemconfig(Request $request)
   {
      $data = $request->only([
         'is_allowed_btn_box'
      ]);

      $system = SystemConf::first();
      try {

         if(is_null($system)){
            $system = SystemConf::create($data);
            $this->createLog('System Config created successfully', 200, 'success', auth()->user()->id);
            flash(__('admin_alert.system_created'))->success();
            return redirect()->route('admin.settings.system');
         }
         else{
            $system->update($data);
            $this->createLog('System Config updated successfully', 200, 'success', auth()->user()->id);
            flash(__('admin_alert.system_update'))->success();
            return redirect()->route('admin.settings.system');
         }
         
      } catch (Exception $e) {
         $this->errorCatch($e->getMessage(), auth()->user()->id);
         flash(__('admin_alert.system_noupdate'))->error();
         return redirect()->route('admin.settings.system');
      }
   }
}
