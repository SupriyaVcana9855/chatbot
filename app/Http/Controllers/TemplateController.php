<?php

namespace App\Http\Controllers;

use App\Http\Requests\TemplatesRequest;
use App\Models\ChatBot;
use App\Models\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function templates(Request $request)
    {
        $search = $request->input('search');
    
        if ($search) {
            $templates = Template::where('temp_title', 'LIKE', '%' . $search . '%')->get();
        } else {
            $templates = Template::all();
        }
    
        // If the request is AJAX, return the partial view with the results
        if ($request->ajax()) {
            return view('templates.template-list', compact('templates'))->render();
        }
    
        return view('templates.template', compact('templates'));
    }
    



    public function templateView($id)
    {
        $templates = Template::where('id', $id)->first();
        // dd($templates );
        return view('templates.viewtemplate', compact('templates'));
    }

    public function addBotTemplate(Request $request)
    {
        $tempData = json_decode($request->tempData, true);
        // dd($tempData);

        if (is_array($tempData)) {
            $chatBot = ChatBot::create([
                'name'              => $request->bot_name,
                'width'              => $tempData['width'] ?? null,
                'type'              =>  $request->type,
                'intro_message'     => $tempData['intro_message'] ?? null,
                'main_color'        => $tempData['main_color'] ?? null,
                'bubble_background' => $tempData['bubble_background'] ?? null,
                'logo'              => $tempData['logo'] ?? null,
                'description'       => $tempData['description'] ?? null,
                'font'              => $tempData['font'] ?? null,
                'font_size'         => $tempData['font_size'] ?? null,
                'bot_position'      => $tempData['bot_position'] ?? null,
                'message_bubble'    => $tempData['message_bubble'] ?? null,
                'radius'            => $tempData['radius'] ?? null,
                'text_alignment'    => $tempData['text_alignment'] ?? null,
                'question_color'    => $tempData['question_color'] ?? null,
                'answer_color'      => $tempData['answer_color'] ?? null,
                'status'            => $tempData['status'] ?? null,
                'header_color'      => $tempData['header_color'] ?? null,
                'background_color'  => $tempData['background_color'] ?? null,
                'option_color'      => $tempData['option_color'] ?? null,
                'option_border_color' => $tempData['option_border_color'] ?? null,
                'button_design'     => $tempData['button_type'] ?? null,
                'button_color'       => $tempData['button_color'] ?? null,
                'button_text_color'       => $tempData['button_text_color'] ?? null,
            ]);

            // Return success or redirect
            return response()->json(['success' => true, 'data' => $chatBot], 200);
        } else {
            // If the tempData is not an array or cannot be decoded
            return response()->json(['error' => 'Invalid data format'], 400);
        }
    }

    public function addTemplateview($id = null){
        $tempData = Template::find($id);
        return view('templates.addtemplate',compact('tempData'));
    }

    public function saveTemplate(TemplatesRequest $request) {
        // Validate the request data
        // dd($request->all());
        // Check if it's an update or create operation
        if ($request->tempDataId) {
            // Find existing template by id
            $tempData = Template::findOrFail($request->tempDataId);
        } else {
            // Create new instance of the Template model
            $tempData = new Template();
        }
    
        // Handle template image upload
        if ($request->hasFile('temp_img')) {
            $file = $request->file('temp_img');
            $fileName = time() . '_' . $file->getClientOriginalName(); 
            $destinationPath = public_path('/assets/images/temp/');
            $file->move($destinationPath, $fileName);
            chmod($destinationPath . $fileName, 0755);
            $tempData->temp_img = $fileName;
        }
    
        // Handle logo upload or use selected avatar
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time() . '.' . $logo->getClientOriginalExtension();
            $destinationPath = public_path('/assets/images/setup/');
            $logo->move($destinationPath, $logoName);
            chmod($destinationPath . $logoName, 0755);
            $tempData->logo = $logoName;
        } else {
            // Use selected avatar if no new logo is uploaded
            $tempData->logo = $request->selected_avatar;
        }
    
        // Handle radius value and append percentage symbol
        $radiusValue = $request->input('radius');
        $radiusValueData = $radiusValue !== null ? $radiusValue . '%' : '';
    
        // Assign request data to the template model fields
        $tempData->name = $request->name;
        $tempData->intro_message = $request->intro_message;
        $tempData->description = $request->description;
        $tempData->font = $request->font;
        $tempData->font_size = $request->font_size;
        $tempData->button_design = $radiusValueData;
        $tempData->text_alignment = $request->text_alignment;
        $tempData->bot_position = $request->position;
        $tempData->radius = $radiusValueData;
        $tempData->main_color = $request->header_color;
        $tempData->header_color = $request->header_color;
        $tempData->background_color = $request->background_color;
        $tempData->question_color = $request->question_color;
        $tempData->answer_color = $request->answer_color;
        $tempData->option_color = $request->option_color;
        $tempData->option_border_color = $request->option_border_color;
        $tempData->temp_title = $request->temp_title;
        $tempData->temp_description = $request->temp_description;
    
        // Save the template data
        $tempData->save();
    
        // Return success or error message based on save result
        if ($tempData) {
            return redirect()->route('templates')->with('success', 'Template saved successfully.');
        } else {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function templateDelete($id){
        $agent = Template::find($id);
        $agent->delete();
        return redirect()->route('templates')->with('success', 'Template deleted successfully.');
    }
}
