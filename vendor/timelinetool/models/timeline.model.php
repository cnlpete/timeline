<?php

namespace Timelinetool\Models;

class Timeline extends Main {

  public function __init() {
    //TODO database
    
  }

  public function getTimelineForHash($sHash) {
    return json_decode('{
    "timeline":
    {
        "headline":"The Main Timeline Headline Goes here",
        "type":"default",
		"startDate":"1888",
		"text":"<p>Intro body text goes here, some HTML is ok</p>",
		"asset":
        {
            "media":"http://yourdomain_or_socialmedialink_goes_here.jpg",
            "credit":"Credit Name Goes Here",
            "caption":"Caption text goes here"
        },
        "date": [
			{
                "startDate":"2011,12,10",
				"endDate":"2011,12,11",
                "headline":"Headline Goes Here",
				"text":"<p>Body text goes here, some HTML is OK</p>",
                "asset":
                {
                    "media":"http://twitter.com/ArjunaSoriano/status/164181156147900416",
                    "credit":"Credit Name Goes Here",
                    "caption":"Caption text goes here"
                }
            }
        ]
    }
}');
  }
}

