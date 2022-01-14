<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Point extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // $request->start = date("d-m-Y H:i:s", strtotime($request->start));
        // $request->stop = date("d-m-Y H:i:s", strtotime($request->stop)); 
        // return parent::toArray($request);
        // dd($this->toArray());
        // return [
        //     'id' => $this->id,
        //     'title' => $this->title,
        //     'lat' => $this->lat,
        //     'lon' => $this->lon,
        //     'start' => $this->start,
        //     'stop' => $this->stop,
        //     'radius' => $this->radius,
        //     'time' => $this->time,
        //     'loc'=> (sizeof($this->lat)==0 || sizeof($this->lon)==0)?true:false
        // ];


        // $request->collection->transform(function ($item, $key) {
        //     if($key=="start")
        //     {
        //         return date("d-m-Y H:i:s", strtotime($item));
        //     }
        //     elseif($key=="stop")
        //     {
        //         return date("d-m-Y H:i:s", strtotime($item));
        //     }
            
            
        // });
        

        // dd($request->collection->all());
        return parent::toArray($request);
    }
}
