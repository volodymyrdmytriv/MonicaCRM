<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ResizeAvatarsCommon implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $avatar_location;
    protected $avatar_file_name;

/**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($avatar_location, $avatar_file_name)
    {
        $this->avatar_location = $avatar_location;
        $this->avatar_file_name = $avatar_file_name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (! $this->avatar_file_name) {
            return;
        }

        $storage = Storage::disk($this->avatar_location);
        if (! $storage->exists($this->avatar_file_name)) {
            return;
        }

        try {
            $avatarFile = $storage->get($this->avatar_file_name);
            $filename = pathinfo($this->avatar_file_name, PATHINFO_FILENAME);
            $extension = pathinfo($this->avatar_file_name, PATHINFO_EXTENSION);
        } catch (FileNotFoundException $e) {
            return;
        }

        $this->resize($avatarFile, $filename, $extension, $storage, 110);
        $this->resize($avatarFile, $filename, $extension, $storage, 174);
    }

    private function resize($avatarFile, $filename, $extension, $storage, $size)
    {
        $avatarFileName = 'avatars/'.$filename.'_'.$size.'.'.$extension;

        $avatar = Image::make($avatarFile);
        $avatar->fit($size);

        $storage->put($avatarFileName, (string) $avatar->stream(), 'public');
    }
}
