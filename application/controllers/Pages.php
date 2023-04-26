<?php

class Pages extends CI_Controller{

    public function view($param = null){
        try {
            $data = array();
            
            if($param == null){
                $page = "home";

                if(!file_exists(APPPATH. 'views/pages/' .$page. '.php')){
                    throw new Exception('File not found');
                }

                $data['title'] = "New Posts";
                $data['posts'] = $this->Posts_model->get_posts();
                $data['total'] = count($data['posts']);

                $this->load->view('templates/header');
                $this->load->view('pages/' .$page, $data);
                $this->load->view('templates/footer');

            } else{
                $page = "single";

                if(!file_exists(APPPATH. 'views/pages/' .$page. '.php')){
                    throw new Exception('File not found');
                }

                $posts = $this->Posts_model->get_posts_single($param);
                if(!is_null($posts)){
                    $data['title'] = $posts['title'];
                    $data['body'] = $posts['body'];
                    $data['date'] = $posts['date_published'];
                    $data['id'] = $posts['id'];
                } else{
                    throw new Exception('Post not found');
                }
                
                $this->load->view('templates/header');
                $this->load->view('pages/' .$page, $data);
                $this->load->view('templates/modal');
                $this->load->view('templates/footer');
            }

        } catch (Exception $e) {
            // Handle the exception here
            show_error($e->getMessage());
        }
    }


    public function search(){

        $page = "home";
        $param = $this->input->post('search');
        

            if(!file_exists(APPPATH.'views/pages/'.$page.'.php')){
                show_404();
            }
    
            $data['title'] = "New Posts";
    
            $this->load->model('Posts_model');
            $data['posts'] = $this->Posts_model->get_posts_search($param);
            $data['total'] = count($data['posts']);

            //print_r($data['document']);
    
            $this->load->view('templates/header');
            $this->load->view('pages/'.$page, $data);
            $this->load->view('templates/footer');
    }


    public function login(){

        $this->form_validation->set_error_delimiters('<div class ="alert alert-danger">','</div>');
        $this->form_validation->set_rules('username', 'username','required');
        $this->form_validation->set_rules('password', 'password','required');

        if($this->form_validation->run() == FALSE){

            $page = "login";

            if(!file_exists(APPPATH.'views/pages/'.$page.'.php')){
                show_404();
            }

            $this->load->view('templates/header');
            $this->load->view('pages/'.$page);
            $this->load->view('templates/footer');

        }else{

            $user_id = $this->Posts_model->login();

            if($user_id){

                $user_data = array(

                    'firstname' => $user_id['firstname'],
                    'lastname' => $user_id['lastname'],
                    'fullname' => $user_id['firstname'].' '.$user_id['lastname'],
                    'access' => $user_id['is_admin'],
                    'logged_in' => true
                );

            $this->session->set_userdata($user_data);
            $this->session->set_flashdata('user_loggedin','You are now logged in as '.$this->session->fullname);
            redirect(base_url());

            } else {
                $this->session->set_flashdata('failed_login', 'Login is invalid');
                redirect('login');
                
            }
    }
}
    public function logout(){

        $this->session->unset_userdata('firstname');
        $this->session->unset_userdata('lastname');
        $this->session->unset_userdata('fullname');
        $this->session->unset_userdata('access');
        $this->session->unset_userdata('logged_in');

        $this->session->set_flashdata('user_loggedout', 'You are now logged out');
        redirect('login');
    }







    public function add(){
        try {
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
            $this->form_validation->set_rules('title', 'title', 'required');
            $this->form_validation->set_rules('body', 'body', 'required');

            if($this->form_validation->run() == FALSE){
                $page = "add";

                if(!file_exists(APPPATH. 'views/pages/' .$page. '.php')){
                    throw new Exception('File not found');
                }

                $data['title'] = "Add New Post";

                $this->load->view('templates/header');
                $this->load->view('pages/' .$page, $data);
                $this->load->view('templates/footer');
            } else{
                $this->Posts_model->insert_post();
                $this->session->set_flashdata('post_added','New Post was added');
                redirect(base_url());
            }
        } catch (Exception $e) {
            // Handle the exception here
            show_error($e->getMessage());
        }
    }

    public function edit($param){
        try {
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
            $this->form_validation->set_rules('title', 'title', 'required');
            $this->form_validation->set_rules('body', 'body', 'required');

            if($this->form_validation->run() == FALSE){
                $page = "edit";

                if(!file_exists(APPPATH. 'views/pages/' .$page. '.php')){
                    throw new Exception('File not found');
                }

                $data['title'] = "Edit Post";
                $posts = $this->Posts_model->get_posts_edit($param);
                if(!is_null($posts)){
                    $data['title'] = $posts['title'];
                    $data['body'] = $posts['body'];
                    $data['date'] = $posts['date_published'];
                    $data['id'] = $posts['id'];
                } else{
                    throw new Exception('Post not found');
                }

                $this->load->view('templates/header');
                $this->load->view('pages/' .$page, $data);
                $this->load->view('templates/footer');
            } else{
                $this->Posts_model->update_post();
                $this->session->set_flashdata('post_updated','Post was updated');
                redirect(base_url(). 'edit/'.$param);
            }
        } catch (Exception $e) {
            // Handle the exception here
            show_error($e->getMessage());
        }
    }

    public function delete(){
        try {
            $this->Posts_model->delete_post();
            $this->session->set_flashdata('post_delete', 'Post was deleted successfully!');
            redirect(base_url());
        } catch (Exception $e) {
            // Handle the exception here
            show_error($e->getMessage());
        }
    }
}
