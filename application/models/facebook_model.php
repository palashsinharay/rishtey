<?php

class facebook_model extends Model{
        	
        function facebook_model(){
                parent::Model();
        }

        function get_facebook_cookie() {
                $app_id             = '345381405552220';
                $application_secret = '2058d2d39f8b71897de2d1ec6f513a30';

                if(isset($_COOKIE['fbs_' . $app_id])){
                        $args = array();
                        parse_str(trim($_COOKIE['fbs_' . $app_id], '\\"'), $args);
                        ksort($args);
                        $payload = '';
                        foreach ($args as $key => $value) {
                                if ($key != 'sig') {
                                $payload .= $key . '=' . $value;
                                }
                        }
                        if (md5($payload . $application_secret) != $args['sig']) {
                                return null;
                        }
                        return $args;
                }
                else{
                        return null;
                }
        }

        function getUser(){
                $cookie = $this->get_facebook_cookie();
                $user = @json_decode(file_get_contents(
                                'https://graph.facebook.com/me?access_token=' .
                                $cookie['access_token']), true);
                return $user;
        }

        function getFriendIds($include_self = TRUE){
                $cookie = $this->get_facebook_cookie();
                $friends = @json_decode(file_get_contents(
                                'https://graph.facebook.com/me/friends?access_token=' .
                                $cookie['access_token']), true);
                $friend_ids = array();
                foreach($friends['data'] as $friend){
                        $friend_ids[] = $friend['id'];
                }
                if($include_self == TRUE){
                        $friend_ids[] = $cookie['uid'];                 
                }       

                return $friend_ids;
        }

        function getFriends($include_self = TRUE){
                $cookie = $this->get_facebook_cookie();
                $friends = @json_decode(file_get_contents(
                                'https://graph.facebook.com/me/friends?access_token=' .
                                $cookie['access_token']), true);
                
                if($include_self == TRUE){
                        $friends['data'][] = array(
                                'name'   => 'You',
                                'id' => $cookie['uid']
                        );                      
                }       

                return $friends['data'];
        }

        function getFriendArray($include_self = TRUE){
                $cookie = $this->get_facebook_cookie();
                $friendlist = @json_decode(file_get_contents(
                                'https://graph.facebook.com/me/friends?access_token=' .
                                $cookie['access_token']), true);
                $friends = array();
                foreach($friendlist['data'] as $friend){
                        $friends[$friend['id']] = $friend['name'];
                }
                if($include_self == TRUE){
                        $friends[$cookie['uid']] = 'You';                       
                }       

                return $friends;
        }
}

?>