<?php

class Visit {
    private $timestamp;
    private $user_agent;
    private $accept_lang;
    private $app_version;
    private $ua;
    private $dt;

    public function os() {
        if (is_null($this->ua)) $this->ua = new phpUserAgent($this->user_agent);
        return $this->ua->getOperatingSystem();
    }

    public function browser() {
        if (is_null($this->ua)) $this->ua = new phpUserAgent($this->user_agent);
        return $this->ua->getBrowserName();
    }

    public function app_version() {
        return $this->app_version;
    }

    public function day() {
        if (is_null($this->dt)) $this->dt = new DateTime($this->timestamp);
        return $this->dt->format('Y-m-d');
    }

    public function req_time() {
        if ($this->request_time < 500)
            return '< 500 ms';
        else if ($this->request_time < 750)
            return '500–750 ms';
        else if ($this->request_time < 1000)
            return '750–1000 ms';
        else if ($this->request_time < 1500)
            return '1000–1500 ms';
        else if ($this->request_time < 2000)
            return '1500–2000 ms';
        else
            return '>2000 ms';
    }

}