<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Patient;

class PatientController extends Controller
{
    // public function list(){
    //     $patients = Patient::orderByDesc('created_at')->get();
        
    //     return Inertia::render('Dashboard',[
    //         'patients' => $patients
    //     ]);
    // }
    public function create(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'ic_no' => 'required|string|max:12',
            'phone_no' => 'required|string',
            'address' => 'required|string|max:255',
            'postcode' => 'required|string|max:5',
            'city' => 'required|string',
            'state' => 'required|string',
        ]);

        $patients = Patient::create([
            'name' => $request -> name,
            'ic_no' => $request -> ic_no,
            'phone_no' => $request -> phone_no,
            'address' => $request -> address,
            'postcode' => $request -> postcode,
            'city' => $request -> city,
            'state' => $request -> state
        ]);
    }

    public function read(Request $request){
        $search = $request->get('search');
        $query = Patient::orderByDesc('created_at');

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('ic_no', 'like', "%{$search}%")
                ->orWhere('phone_no', 'like', "%{$search}%");
        }

        $patients = $query->paginate(5);
        
        return response()->json($patients);
    }

    public function update(Request $request, $id){
        $patient = Patient::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'ic_no' => 'required|string|max:12',
            'phone_no' => 'required|string',
            'address' => 'required|string|max:255',
            'postcode' => 'required|string|max:5',
            'city' => 'required|string',
            'state' => 'required|string',
        ]);

        $patient->name = $request->get('name');
        $patient->ic_no = $request->get('ic_no');
        $patient->phone_no = $request->get('phone_no');
        $patient->address = $request->get('address');
        $patient->postcode = $request->get('postcode');
        $patient->city = $request->get('city');
        $patient->state = $request->get('state');

        $patient->save();
    }

    public function delete($id){
        $patient = Patient::findOrFail($id);

        $result = $patient->delete();
        if($result){
            return ['result'=>'Record has been deleted'];
        }
    }

    public function search(Request $request){
        $data = $request->get('v');

        $patients = Patient::where('name', 'like', "%{$data}%")
                ->orWhere('ic_no', 'like', "%{$data}%")
                ->orWhere('phone_no', 'like', "%{$data}%")
                ->get();

        return response()->json($patients);
    }
}
