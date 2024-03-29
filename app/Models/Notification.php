<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ramsey\Uuid\UuidInterface;

class Notification extends Model
{
    public string $title;
    public ?string $icon = "check";
    public ?string $color = "green";
    public ?string $description = null;
    public ?string $id;

    protected $connection = null;
    protected $primaryKey = null;
    protected $keyType = "string";

    public function save(array $options = []): self
    {
        if (empty($this->id)) $this->id = Str::uuid();

        $notifications = session("notifications");
        $notifications[$this->id] = $this;
        session(["notifications" => $notifications]);
        return $this;
    }

    public function show(): string
    {
        $notifications = session("notifications");
        unset($notifications[$this->id]);
        session(["notifications" => $notifications]);

        $svg = $this->iconToSVG();

        return '
        <!-- Global notification live region, render this permanently at the end of the document -->
    <div class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 z-50" id="notif-' . $this->id . '">
      <div class="p-4">
        <div class="flex items-start">
          <div class="flex-shrink-0">
            <svg class="h-6 w-6 text-' . $this->color . '-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
              ' . $svg . '
            </svg>
          </div>
          <div class="ml-3 w-0 flex-1 pt-0.5">
            <p class="text-sm font-medium text-gray-900">' . $this->title . '</p>
            <p class="mt-1 text-sm text-gray-500">' . $this->description . '</p>
          </div>
          <div class="ml-4 flex flex-shrink-0">
            <button type="button" id="notif-' . $this->id . '-button" class="inline-flex rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
              <span class="sr-only">Close</span>
              <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>

<div class="text-green-400" style="display: none;"></div>

<script>
    $(document).ready(function () {
        $("#notif-' . $this->id . '-button").click(function() {
            hide();
        });

        function hide() {
            $("#notif-' . $this->id . '").hide("fast")
        }

        setTimeout(hide, 5000);
    });
</script>

';
    }

    private function iconToSVG(){
        return match ($this->icon) {
            "check" => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
            "exclamation-circle" => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />'
        };
    }
}
