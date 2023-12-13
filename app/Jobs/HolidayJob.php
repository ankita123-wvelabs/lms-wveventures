<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Holiday;

class HolidayJob
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!isset($this->data['id'])) {
            $this->data['id'] = null;
        }

        $data = Holiday::firstOrNew(['id' => $this->data['id']]);

        if (isset($this->data['image'])) {
        
            $old_image = $data['image'];
        
            if(file_exists(public_path($old_image)) && $old_image != null) {
                unlink(public_path($old_image));
            }

            $image = $this->data['image'];
            $name = time() . '.' . $image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/holiday');
            $image->move($destinationPath, $name);
            $this->data['image'] = 'uploads/holiday/' . $name;
        }

        $data->fill($this->data);
        $data->save();
    }
}
