<?php
/**
 * Helper for views related to events.
 *
 * PHP version 5
 *
 * @category  Joind.in
 * @package   Controllers
 * @author    Chris Cornutt <chris@joind.in>
 * @author    Mike van Riel <mike.vanriel@naenius.com>
 * @copyright 2009 - 2010 Joind.in
 * @license   http://github.com/joindin/joind.in/blob/master/doc/LICENSE JoindIn
 * @link      http://github.com/joindin/joind.in
 */


/**
 * Build the code for a session, used in determining the claim status.
 *
 * @param string $tid          Name of the ?
 * @param int    $eid          Id of the event
 * @param string $title        Title of the session
 * @param string $speaker_name Name of the speaker
 *
 * @return string
 */
function buildCode($tid, $eid, $title, $speaker_name)
{
    $speaker_name = trim($speaker_name);
    $str = 'ec' . str_pad(substr($tid, 0, 2), 2, 0, STR_PAD_LEFT) .
        str_pad($eid, 2, 0, STR_PAD_LEFT);
    $str .= substr(md5($title . $speaker_name), 5, 5);
    return $str;
}

/**
 * Given the full list of claimed talks (event_model->getClaimedTalks),
 * find the number of times they've been claimed.
 *
 * @param array List of claimed talks
 *
 * @return int
 */
function buildTimesClaimed($claimed_talks)
{
    $times_claimed = array();

    foreach ($claimed_talks as $k => $v) {
        if (isset($times_claimed[$v->rid])) {
            $times_claimed[$v->rid]++;
        } else {
            $times_claimed[$v->rid] = 1;
        }
    }
    return $times_claimed;
}

/**
 * Given the full list of claimed talks (event_model->getClaimedTalks),
 * find the user IDs with claims.
 *
 * @param array $claimed_talks The array of claimed talks
 *
 * @return int[]
 */
function buildClaimedUids($claimed_talks)
{
    $claimed_uids = array();

    foreach ($claimed_talks as $k => $v) {
        $claimed_uids[$v->rid] = $v->uid;
    }

    return $claimed_uids;
}

/**
 * Given the full list of claimed talks (event_model->getClaimedTalks),
 * figure out the user IDs that have claims in there and the count of
 * claims on a session.
 *
 * @param array $claimed_talks The array of claimed talks
 *
 * @return array[]
 */
function buildClaimDetail($claimed_talks)
{
    $claim_detail = array(
        'uids' => array(),
        'claim_count' => array()
    );

    foreach ($claimed_talks as $k => $v) {
        $claim_detail['uids'][$v->rid] = $v->uid;

        if (isset($times_claimed[$v->rid])) {
            $claim_detail['claim_count'][$v->rid]++;
        } else {
            $claim_detail['claim_count'][$v->rid] = 1;
        }
    }

    return $claim_detail;
}

/**
 * Given the full list of claimed talks (event_model->getClaimedTalks),
 * build an overview of claims.
 *
 * @param array $claimed_talks
 *
 * @return array
 */
function buildClaims($claimed_talks)
{
    $claims = array();

    foreach ($claimed_talks as $talk) {
        $claims[$talk->talk_id][$talk->full_name] = array(
            'uid' => $talk->user_id,
            'rcode' => $talk->rcode
        );
    }

    return $claims;
}

/**
 * Given the full list of sessions, finds which of them given have slides.
 *
 * @param array $sessions
 *
 * @return array
 */
function buildSlidesList($sessions)
{
    $slides_list = array();

    foreach ($sessions as $s) {
        $speaker_list = array();

        if (!empty($s->slides_link)) {
            foreach ($s->speaker as $name) {
                $speaker_list[] = $name->speaker_name;
            }

            $slides_list[$s->ID] = array(
                'link'    => $s->slides_link,
                'speaker' => implode(', ', $speaker_list),
                'title'   => $s->talk_title
            );
        }
    }

    return $slides_list;
}

/**
 * Return true or false depending on whether the event is currently on.
 *
 * @param int $event_start Timestamp representing the start of the event
 * @param int $event_end   Timestamp representing the end of the event
 *
 * @return bool
 */
function event_isNowOn($event_start, $event_end)
{
    $time = time();
    return ($time > $event_start && $time < $event_end);
}

/**
 * Takes an event, and attempts to add a flag to say whether the event is on
 * now.
 *
 * @param object $event
 *
 * @return object
 */
function event_decorateNow($event)
{
    $event->now = (event_isNowOn($event->event_start, $event->event_end))
        ? "now" : "";

    return $event;
}

/**
 * Takes an array of events, and attempts to add a flag to each one to say
 * whether the event is on now.
 *
 * @param object[] $events
 *
 * @return object[]
 */
function event_listDecorateNow($events)
{
    foreach ($events as $key => $event) {
        $events[$key] = event_decorateNow($events[$key]);
    }

    return $events;
}

/**
 * Create the stats for an event's talks.
 *
 * @param array $talks Talk comment data
 *
 * @return array Contains total comment count and rating average
 */
function buildTalkStats($talks)
{
    $rating = 0;

    if (is_array($talks)) {
        foreach ($talks as $talk) {
            $rating += $talk->rating;
        }
    }
    $avg = (count($talks) > 0) ? $rating / count($talks) : $rating;

    return array(
        'comments_total' => count($talks),
        'rating_avg' => $avg
    );
}