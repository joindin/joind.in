<h1>Register a new account</h1>

<?php 
$msg=$this->session->flashdata('msg');
if (!empty($msg)): 
?>
<?php $this->load->view('msg_info', array('msg' => $msg)); ?>
<?php endif; ?>

<div class="box">

    <p>
        Use the form below to register a new account for the site. 
        Username, password and email address fields are required.
    </p>
    
    <?php echo form_open('user/register', array('class' => 'form-register')); ?>
    
    <?php if (!empty($this->form_validation->error_string)): ?>
            <?php $this->load->view('msg_error', array('msg' => $this->form_validation->error_string)); ?>
    <?php endif; ?>

	<div class="row">
    	<label for="user">Username</label>
    	<?php echo form_input(array('name' => 'user', 'id' => 'user'), $this->input->post('user')); ?>
    
        <div class="clear"></div>
    </div>
    
    <div class="row">
    	<label for="pass">Password</label>
    	<?php echo form_input(array('name' => 'pass', 'id' => 'pass', 'type' => 'password')); ?>
    
        <div class="clear"></div>
    </div>
    
    <div class="row">
    	<label for="passc">Confirm Password</label>
    	<?php echo form_input(array('name' => 'passc', 'id' => 'passc', 'type' => 'password')); ?>
    
        <div class="clear"></div>
    </div>
    
    <div class="row">
    	<label for="email">Email</label>
    	<?php echo form_input(array('name' => 'email', 'id' => 'email'), $this->input->post('email')); ?>
    
        <div class="clear"></div>
    </div>
    
    <div class="row">
    	<label for="full_name">Full Name</label>
    	<?php echo form_input(array('name' => 'full_name', 'id' => 'full_name'), $this->input->post('full_name')); ?>
    
        <div class="clear"></div>
    </div>

	<div class="row">
    	<label for="twitter">Twitter Username</label>
    	<?php echo form_input(array('name' => 'twitter_username', 'id' => 'twitter_username'), $this->input->post('twitter_username')); ?>
        <div class="clear"></div>
    </div>

	<div class="row row-buttons">
    	<?php echo form_submit(array('name' => 'sub', 'class' => 'btn-big'), 'Register'); ?>
    </div>

    <?php echo form_close(); ?>
</div>

