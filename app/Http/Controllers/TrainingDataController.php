<?php

namespace App\Http\Controllers;

use App\TrainingData;
use Illuminate\Http\Request;

use App\Devices;

class TrainingDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TrainingData  $trainingData
     * @return \Illuminate\Http\Response
     */
    public function show(TrainingData $trainingData, $id)
    {
        $training_data = TrainingData::findOrFail($id);
        $file = fopen($training_data->csv_path, 'r');
        while ($data = fgetcsv($file)) { //每次读取CSV里面的一行内容
            //print_r($data); //此为一个数组，要获得每一个数据，访问数组下标即可
            $csv[] = $data;
        }
        fclose($file);
        return view('trainingDataDetail')->with(['training_data' => $training_data, 'csv' => $csv]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TrainingData  $trainingData
     * @return \Illuminate\Http\Response
     */
    public function edit(TrainingData $trainingData)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TrainingData  $trainingData
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TrainingData $trainingData)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TrainingData  $trainingData
     * @return \Illuminate\Http\Response
     */
    public function destroy(TrainingData $trainingData)
    {
        //
    }
    
    
    public function post_entry(Request $request)
    {
        $device = Devices::all()->where('device_id', $request->input('device-id'))->first();

        if ($request->header == 'register') {
            $device->register_status = 1;
            $device->save();
            return 'success';
        } elseif ($request->header == 'check') {
            return $device->register_status ? 'yes' : 'no';
        } elseif ($request->header == 'training') {
            if ($request->status == 'start') {
                $csv_folder_path = "csv/user_" . $device->user_id . "/";
                $csv_file_name = date("Y-m-d-G-i", time()) . ".csv";

                if (!is_dir($csv_folder_path)) {
                    mkdir($csv_folder_path, 0777, true);
                }

                $training_data = new TrainingData;
                $training_data->user_id = $device->user_id;
                $training_data->device_id = $device->device_id;
                $training_data->device_type = $device->device_type;
                $training_data->csv_path = $csv_folder_path . $csv_file_name;
                $training_data->start_time = time();
                $training_data->finished = 0;
                $training_data->save();

                $csv_file_path = $training_data->csv_path;

                $timestamp = $request->input('timestamp');
    
                $header = array("Timestamp", "Finger1", "Finger2", "Finger3", "Finger4", "Finger5");
                $fp = fopen($csv_file_path, 'a');
                fputcsv($fp, $header);
                fclose($fp);

                return $training_data->id;
                
            } elseif ($request->status == 'end') {
                $training_data = TrainingData::findOrFail($request->input('data-id'));
                $training_data->end_time = time();
                $training_data->duration_time = date("i:s", $training_data->end_time - $training_data->start_time);
                $training_data->finished = 1;
                $training_data->save();
                return "success";
            } elseif ($request->status == 'doing') {
                $training_data = TrainingData::findOrFail($request->input('data-id'));
                $csv_file_path = $training_data->csv_path;

                $timestamp = $request->input('timestamp');
                // $finger1 = intval($request->input('finger1'))/100.0;
                // $finger2 = intval($request->input('finger2'))/100.0;
                // $finger3 = intval($request->input('finger3'))/100.0;
                // $finger4 = intval($request->input('finger4'))/100.0;
                // $finger5 = intval($request->input('finger5'))/100.0;
                $finger1 = $request->input('finger1');
                $finger2 = $request->input('finger2');
                $finger3 = $request->input('finger3');
                $finger4 = $request->input('finger4');
                $finger5 = $request->input('finger5');
    
                // $header = array("Timestamp", "Finger1", "Finger2", "Finger3", "Finger4", "Finger5");
                $list = array($timestamp, $finger1, $finger2, $finger3, $finger4, $finger5);
                $fp = fopen($csv_file_path, 'a');
                // fputcsv($fp, $header);
                fputcsv($fp, $list);
                fclose($fp);
                return "success";
            } else {
                return "failed";
            }
        }
    }
}
