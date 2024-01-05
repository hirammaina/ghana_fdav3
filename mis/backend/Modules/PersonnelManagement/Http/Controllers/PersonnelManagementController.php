<?php

namespace Modules\PersonnelManagement\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PersonnelManagementController extends Controller
{
  protected $user_id;

    public function __construct(Request $req)
    {
        $is_mobile = $req->input('is_mobile');
        if (is_numeric($is_mobile) && $is_mobile > 0) {
            $this->user_id = $req->input('user_id');
        } else {
            $this->middleware(function ($request, $next) {
                if (!\Auth::check()) {
                    $res = array(
                        'success' => false,
                        'message' => '<p>NO SESSION, SERVICE NOT ALLOWED!!<br>PLEASE RELOAD THE SYSTEM!!</p>'
                    );
                    echo json_encode($res);
                    exit();
                }
                $this->user_id = \Auth::user()->id;
                return $next($request);
            });
        }
    }
    public function index()
    {
        return view('personnelmanagement::index');
    }
    public function savePersonnelImage(Request $req)
{
    $user_id = $req->input('id');
    $res = array();
    try {
        if ($req->hasFile('profile_photo')) {
            $ben_image = $req->file('profile_photo');
            $origImageName = $ben_image->getClientOriginalName();
            $extension = $ben_image->getClientOriginalExtension();
            $destination = getcwd() . '\resources\images\personnel-profile';
            $savedName = str_random(5) . time() . '.' . $extension;
            
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array(strtolower($extension), $allowedExtensions)) {
                $res = array(
                    'success' => false,
                    'message' => 'Invalid file type. Only image files (jpg, jpeg, png, gif) are allowed.'
                );
                return response()->json($res);
            }
            
            $ben_image->move($destination, $savedName);
            $where = array(
                    'user_id' => $user_id
                );
                if ($user_id != '') {
                    $recordExists = recordExists('par_personnel_images', $where);
                    if ($recordExists) {
                        $update_params = array(
                            'initial_name' => $origImageName,
                            'saved_name' => $savedName,
                            'updated_by' => \Auth::user()->id
                        );
                        DB::table('par_personnel_images')
                            ->where($where)
                            ->update($update_params);
                    } else {
                        $insert_params = array(
                            'user_id' => $user_id,
                            'initial_name' => $origImageName,
                            'saved_name' => $savedName,
                            'created_by' => \Auth::user()->id
                        );
                        DB::table('par_personnel_images')
                            ->insert($insert_params);
                    }
                } else {
                    $insert_params = array(
                        'user_id' => $user_id,
                        'initial_name' => $origImageName,
                        'saved_name' => $savedName,
                        'created_by' => \Auth::user()->id
                    );
                    DB::table('par_personnel_images')
                        ->insert($insert_params);
                }
                $res = array(
                    'success' => true,
                    'image_name' => $savedName,
                    'message' => 'Image uploaded successfully!!'
                );
            }
        } catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return response()->json($res);
    }
   
    public function savePersonnelInformation(Request $req)
    {
        try {
            $user_id = \Auth::user()->id;
            $post_data = $req->all();
            $table_name =$req->input('table_name');
            $id = $post_data['id'];

            //$ben_image = $req->file('profile_photo');
            //unset unnecessary values
            unset($post_data['_token']);
            unset($post_data['table_name']);
            unset($post_data['model']);
            unset($post_data['model']);
            unset($post_data['id']);
           
            $table_data = $post_data;

            //add extra params
            $table_data['created_on'] = Carbon::now();
            $table_data['created_by'] = $user_id;
            $where = array(
                'id' => $id
            );
            $res = array();
            if (isset($id) && $id != "") {
                if (recordExists($table_name, $where)) {
                    unset($table_data['created_on']);
                    unset($table_data['created_by']);
                    $table_data['dola'] = Carbon::now();
                    $table_data['altered_by'] = $user_id;
                    $previous_data = getPreviousRecords($table_name, $where);
                        if ($previous_data['success'] == false) {
                            return $previous_data;
                        }
                    $previous_data = $previous_data['results'];
                    $res =  updateRecord($table_name, $previous_data, $where, $table_data, $user_id);
                }
            } else {
                $res = insertRecord($table_name, $table_data, $user_id);
            }
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return response()->json($res);
    }




       public function savePvPersonnelInformation(Request $req)
    {
        try {
            $user_id = \Auth::user()->id;
            $post_data = $req->all();
            $table_name =$req->input('table_name');
            $id = $post_data['id'];

            //unset unnecessary values
            unset($post_data['_token']);
            unset($post_data['table_name']);
            unset($post_data['model']);
            unset($post_data['model']);
            unset($post_data['id']);
           
            $table_data = $post_data;

            //add extra params
            $table_data['created_on'] = Carbon::now();
            $table_data['created_by'] = $user_id;
            $where = array(
                'id' => $id
            );
            $res = array();
            if (isset($id) && $id != "") {
                if (recordExists($table_name, $where)) {
                    unset($table_data['created_on']);
                    unset($table_data['created_by']);
                    $table_data['dola'] = Carbon::now();
                    $table_data['altered_by'] = $user_id;
                    $previous_data = getPreviousRecords($table_name, $where);
                        if ($previous_data['success'] == false) {
                            return $previous_data;
                        }
                    $previous_data = $previous_data['results'];
                    $res =  updateRecord($table_name, $previous_data, $where, $table_data, $user_id);
                }
            } else {
                $res = insertRecord($table_name, $table_data, $user_id);
            }
        } catch (\Exception $exception) {
            $res = sys_error_handler($exception->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        } catch (\Throwable $throwable) {
            $res = sys_error_handler($throwable->getMessage(), 2, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1), explode('\\', __CLASS__), \Auth::user()->id);
        }
        return response()->json($res);
    }
   


   public function getDrugShopInchargesDetails(Request $req){
        $table_name =$req->input('table_name');

        try {
            $results = DB::table($table_name.' as t1')
                ->leftjoin('par_countries as t3', 't1.country_id', '=', 't3.id')
                ->leftJoin('par_countries as t4','t1.country_id','=','t4.id')
                ->leftJoin('par_regions as t5','t1.region_id','=','t5.id')
                ->leftJoin('par_districts as t6','t1.district_id','=','t6.id')
                ->leftJoin('par_personnel_qualifications as t7','t1.qualification_id','=','t7.id')
                ->leftJoin('par_gender as t8','t1.gender_id','=','t8.id')
                ->select('t1.*', 't4.name as country_name', 't5.name as region_name','t6.name as district_name','t7.name as qualification_name','t8.name as gender')
                ->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well!!'
            );
        } catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return \response()->json($res);
    }



      public function getPvPersonnelDetails(Request $req){
        $table_name =$req->input('table_name');

        try {
            $results = DB::table($table_name.' as t1')
                ->leftjoin('par_countries as t3', 't1.country_id', '=', 't3.id')
                ->leftJoin('par_countries as t4','t1.country_id','=','t4.id')
                ->leftJoin('par_regions as t5','t1.region_id','=','t5.id')
                ->leftJoin('par_districts as t6','t1.district_id','=','t6.id')
                ->leftJoin('par_pv_personnel_designation as t7','t1.designation_id','=','t7.id')
                ->leftJoin('par_gender as t8','t1.gender_id','=','t8.id')
                ->select('t1.*', 't4.name as country_name', 't5.name as region_name','t6.name as district_name','t7.name as designation_name','t8.name as gender')
                ->get();
            $res = array(
                'success' => true,
                'results' => $results,
                'message' => 'All is well!!'
            );
        } catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return \response()->json($res);
    }



      public function deletePersonnelRecord(Request $req)
    {
        try {
            $record_id = $req->input('id');
            $table_name = $req->input('table_name');
            $user_id = \Auth::user()->id;
            $where = array(
                'id' => $record_id
            );
            $previous_data = getPreviousRecords($table_name, $where);
            if ($previous_data['success'] == false) {
                return $previous_data;
            }
            $previous_data = $previous_data['results'];
            $res = deleteRecord($table_name, $previous_data, $where, $user_id);
        } catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return response()->json($res);
    }

      public function activatePersonelRecord(Request $req){
        try {
            $record_id = $req->input('id');
            $table_name = $req->input('table_name');
            $user_id = \Auth::user()->id;
            $where = array(
                'id' => $record_id
            );
            $previous_data = getPreviousRecords($table_name, $where);
            if ($previous_data['success'] == false) {
                return $previous_data;
            }
            $previous_data = $previous_data['results'];
            $table_data = array('is_active'=>1);
            $res =  updateRecord($table_name, $previous_data, $where, $table_data, $user_id);
        } catch (\Exception $exception) {
            $res = array(
                'success' => false,
                'message' => $exception->getMessage()
            );
        } catch (\Throwable $throwable) {
            $res = array(
                'success' => false,
                'message' => $throwable->getMessage()
            );
        }
        return response()->json($res);
    }



    public function deactivatePersonnel(Request $req)
    {
        $user_id = $req->input('id');
        $email = $req->input('email');
        $reason = $req->input('reason');
        $table_name = $req->input('table_name');
        try {
            $params = array(
                'personnel_id' => $user_id,
                'email' => $email,
                'reason' => $reason,
                'action_by' => \Auth::user()->id,
                'date' => Carbon::now()
            );
            DB::table('tra_deactivated_personnels')
                ->insert($params);
            $where = array('id'=>$user_id);
            $data = array('is_active'=>0);
            DB::table($table_name)->where($where)->update($data);
            $res = array(
                'success' => true,
                'message' => 'User deactivated successfully!!'
            );
        } catch (\Exception $e) {
            $res = array(
                'success' => false,
                'message' => $e->getMessage()
            );
        }
        return response()->json($res);
    }

}
