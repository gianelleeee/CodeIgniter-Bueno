<?php if($this->session->flashdata('post_updated')) : ?>
    <?= '<p class="alert alert-success">'.$this->session->flashdata('post_updated').'</p>' ;?>
<?php endif;?>



<h1><?= $title; ?></h1>
<hr>

<?= validation_errors();?>

<div class="row">

    <?= form_open('edit/'.$id);?>
    <div class="col-lg-12">
    <input type="text" name="title" class="form-control" placeholder="Enter Post Title" value="<?=$title;?>">
    
    </div>
    
    <br/>
    
    <div class="form-group">
        <textarea name="body" id="" cols="30" rows="5" class="form-control" placeholder="Enter Post Details"><?= $body; ?></textarea>
    
    
    </div>
   
    </div>
<div class="mt-4">
    
    <input type="hidden" name="id" value="<?= $id; ?>">

    <button type="submit" name="submit" class="btn btn-primary">Update</button>

</div> 