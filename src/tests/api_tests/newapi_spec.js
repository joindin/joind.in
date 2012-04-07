// ./node_modules/.bin/jasmine-node . 
var frisby = require('frisby');
var util   = require('util');

var baseURL = "http://api.joind.in";

frisby.globalSetup({ // globalSetup is for ALL requests
    request: {
      headers: { 'Content-type': 'application/json' }
    }
});

frisby.create('Initial discovery')
  .get(baseURL)
  .expectStatus(200)
  .expectHeader("content-type", "application/json; charset=utf8")
  .inspectJSON()
  .expectJSON({
    'events'          : baseURL + '/v2/events',
    'hot-events'      : baseURL + '/v2/events?filter=hot',
    'upcoming-events' : baseURL + '/v2/events?filter=upcoming',
    'past-events'     : baseURL + '/v2/events?filter=past',
    'open-cfps'       : baseURL + '/v2/events?filter=cfp'
  })
  .afterJSON(function(apis) {

    // Loop over all of the event types
    for (var evType in apis) {

      frisby.create('Event list for ' + evType)
        .get(apis[evType])
        .expectStatus(200)
        .expectHeader("content-type", "application/json; charset=utf8")
        .afterJSON(function(ev) {

          // Check meta-data
          expect(ev.meta).toContainJsonTypes({"count":Number});

          for (var i in ev) {
            if (!isNaN(i)) {
            	// Numeric ID, therefore an actual event

            	// Check optional fields, only if they have data present
              if (ev[i].href != null) {
                expect(ev[i].href).toBeDefined();
                expect(typeof ev[i].href).toBe('string');
                if (ev[i].href != '') {
                  //expect(ev[i].href).toMatch(/^http/);
  			    }
              }
              if (ev[i].icon != null) {
                expect(ev[i].icon).toBeDefined();
                expect(typeof ev[i].icon).toBe('string');
              }

            	// Check required fields
              expect(ev[i].name).toBeDefined();
              expect(ev[i].start_date).toBeDefined();
              expect(ev[i].end_date).toBeDefined();
              expect(ev[i].description).toBeDefined();
              expect(ev[i].href).toBeDefined();
              expect(ev[i].icon).toBeDefined();
              expect(ev[i].attendee_count).toBeDefined();
              expect(ev[i].uri).toBeDefined();
              expect(ev[i].verbose_uri).toBeDefined();
              expect(ev[i].comments_uri).toBeDefined();
              expect(ev[i].talks_uri).toBeDefined();
              expect(ev[i].website_uri).toBeDefined();
              expect(typeof ev[i].name).toBe('string');
              expect(typeof ev[i].start_date).toBe('string');
              expect(typeof ev[i].end_date).toBe('string');
              expect(typeof ev[i].description).toBe('string');
              expect(typeof ev[i].attendee_count).toBe('number');
              expect(typeof ev[i].uri).toBe('string');
              expect(typeof ev[i].verbose_uri).toBe('string');
              expect(typeof ev[i].comments_uri).toBe('string');
              expect(typeof ev[i].talks_uri).toBe('string');
              expect(typeof ev[i].website_uri).toBe('string');

  			// Check for more detail in the events
              frisby.create('Event detail for ' + ev[i].name)
                .get(ev[i].verbose_uri)
                .expectStatus(200)
                .expectHeader("content-type", "application/json; charset=utf8")
                .afterJSON(function(detailedEv) {
                  expect(detailedEv[0]).toBeDefined();
                  expect(typeof detailedEv[0]).toBe('object');
                  var evt = detailedEv[0];
                  expect(evt.name).toBeDefined();
                  expect(evt.start_date).toBeDefined();
                  expect(evt.end_date).toBeDefined();
                  expect(evt.description).toBeDefined();
                  expect(evt.href).toBeDefined();
                  expect(evt.icon).toBeDefined();
                  expect(evt.latitude).toBeDefined();
                  expect(evt.longitude).toBeDefined();
                  expect(evt.tz_continent).toBeDefined();
                  expect(evt.tz_place).toBeDefined();
                  expect(evt.location).toBeDefined();
                  expect(evt.attendee_count).toBeDefined();
                  expect(evt.comments_enabled).toBeDefined();
                  expect(evt.event_comment_count).toBeDefined();
                  expect(evt.cfp_start_date).toBeDefined();
                  expect(evt.cfp_end_date).toBeDefined();
                  expect(evt.cfp_url).toBeDefined();
                  expect(evt.uri).toBeDefined();
                  expect(evt.verbose_uri).toBeDefined();
                  expect(evt.comments_uri).toBeDefined();
                  expect(evt.talks_uri).toBeDefined();
                  expect(evt.website_uri).toBeDefined();
                  expect(evt.all_talk_comments_uri).toBeDefined();

                  expect(typeof evt.name).toBe('string', "Event name");
                  expect(typeof evt.start_date).toBe('string');
                  expect(typeof evt.end_date).toBe('string');
                  expect(typeof evt.description).toBe('string');
                  if (evt.href != null) {
                    expect(typeof evt.href).toBe('string', "Event href");
                    if (evt.href != '') {
                      //expect(evt.href).toMatch(/^http/);
                    }
                  }
                  if (evt.icon != null) {
                    expect(typeof evt.icon).toBe('string');
                  }
                  expect(typeof evt.latitude).toBe('number');
                  expect(typeof evt.longitude).toBe('number');
                  expect(typeof evt.tz_continent).toBe('string');
                  expect(typeof evt.tz_place).toBe('string');
                  expect(typeof evt.location).toBe('string');
                  expect(typeof evt.attendee_count).toBe('number');
                  expect(typeof evt.comments_enabled).toBe('number');
                  expect(typeof evt.event_comment_count).toBe('number');
                  if (evt.cfp_start_date != null) {
                    expect(typeof evt.cfp_start_date).toBe('string');
                  }
                  if (evt.cfp_end_date != null) {
                    expect(typeof evt.cfp_end_date).toBe('string');
                  }
                  if (evt.cfp_url != null) {
                    expect(typeof evt.cfp_url).toBe('string');
                  }
                  expect(typeof evt.uri).toBe('string');
                  expect(typeof evt.verbose_uri).toBe('string');
                  expect(typeof evt.comments_uri).toBe('string');
                  expect(typeof evt.talks_uri).toBe('string');
                  expect(typeof evt.website_uri).toBe('string');
                  expect(typeof evt.all_talk_comments_uri).toBe('string');


                  frisby.create('Event comments for ' + evt.name)
                    .get(evt.comments_uri)
                    .expectStatus(200)
                    .expectHeader("content-type", "application/json; charset=utf8")
                    .afterJSON(function(detailedEv) {
					})
				  .toss();




                })
              .toss();
            }
  		  }
        })
      .toss();
    }

  })
.toss();


