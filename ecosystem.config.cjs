module.exports = {
  "apps": [
    {
      "name": "laravel-app",
      "script": "php",
      "args": "artisan serve --host=0.0.0.0 --port=8000",
      "cwd": "/home/asfung/Dev/Talker/TServer",
      "interpreter": "none",
      "watch": false
    },
    {
      "name": "laravel-queue",
      "script": "php",
      "args": "artisan queue:work --sleep=3 --tries=3",
      "cwd": "/home/asfung/Dev/Talker/TServer",
      "interpreter": "none",
      "watch": false
    },
    {
      "name": "laravel-schedule",
      "script": "php",
      "args": "artisan schedule:work",
      "cwd": "/home/asfung/Dev/Talker/TServer",
      "interpreter": "none",
      "watch": false
    }
  ]
}
