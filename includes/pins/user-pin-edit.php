<?php

class UserPinEdit {

  function __construct() {
  }

  function get_edit_nonce() {
    return wp_create_nonce('user_' . $this->recordId);
  }

  function get_record_id() {
    return $this->recordId;
  }

  function get_record() {
    return $this->record;
  }

  function get_filters() {
    return array(
      'WORKSHOP' => 'I have a Precious Plastic workspace',
      'MACHINE' => 'I sell machines / can build machines for others',
      'STARTED' => 'I want to find people locally to help me get started'
    );
  }

  function get_statuses() {
    return array(
      'OPEN' => 'Yes of course!',
      'CLOSED' => 'No, I am shy :)'
    );
  }

  function prepare_items() {
    $recordId = $_REQUEST['id'];
    $this->recordId = $recordId;

    if (empty($recordId)) {
      $this->record = null;
      return;
    }

    global $wpdb;

    $query = "SELECT name, lat, lng, address, description, filters, imgs, status FROM pp_pins WHERE ID = " . $recordId . ' AND user_ID = ' . get_current_user_id();

    $this->record = $wpdb->get_results($query)[0];
  }
}

$table = new UserPinEdit();
$table->prepare_items();
$record = $table->get_record();
$filters = $table->get_filters();
$statuses = $table->get_statuses();
?>

<h2 class='pin-edit__title'>
  <?php 
    if ($table->get_record_id()) {
      echo "Editing '$record->name'";  
    } else {
      echo "Add New Pin";
    }; ?>
</h2>

<form id="pin-edit" class="pin-edit" method="POST" enctype="multipart/form-data" action="<?php echo $url=strtok($_SERVER["REQUEST_URI"],'?'); ?>">
  <input type="hidden" name="action" value="edit_pin" />
  <input type="hidden" name="_wpnonce" value="<?php echo $table->get_edit_nonce(); ?>" />
  <input type="hidden" name="id" value="<?php echo $table->get_record_id(); ?>" />

  <input type="hidden" id="lat" name="lat" value="<?php echo $record->lat; ?>" />
  <input type="hidden" id="lng" name="lng" value="<?php echo $record->lng; ?>" />

  <div class="pin-edit__field">
    <label class="pin-edit__label" for="address">Type your pin address, so we can locate it on the map.</label>
    <input class="pin-edit__input" placeholder="Amsterdam, The Netherlands" type="text" id="address" name="address" maxlength="200" value="<?php echo $record->address; ?>">
    <p class="pin-edit__error pin-edit__error--address">Address is required</p>
  </div>

  <div class="pin-edit__field pin-edit__map">
    <label class="pin-edit__label">Click and drag the pin to the location you would like to show on the map.</label>
    <div id="pin-edit-map"></div>
    <p class="pin-edit__error pin-edit__error--map">Map pin is required</p>
  </div>

  <fieldset class="pin-edit__field">
    <legend class="pin-edit__label">How are you involved with Precious Plastic?</legend>
    <?php
    foreach ($filters as $key => $value) {
      $checked = !empty($record->filters) && in_array($key, json_decode($record->filters)) ? "checked" : "";
      echo "<div class='pin-edit__choice'>
        <input type='checkbox' id='$key' name='filters[]' value='$key' $checked>
        <label for='workshop'>$value</label>
      </div>";
    }
    ?>
    <p class="pin-edit__error pin-edit__error--filters">At least one option must be selected</p>
  </fieldset>

  <div class="pin-edit__field">
    <label class="pin-edit__label" for="name">What's the name of your pin?</label>
    <input class="pin-edit__input" type="text" id="name" name="name" maxlength="200" value="<?php echo $record->name; ?>">
    <p class="pin-edit__error pin-edit__error--name">Name is required</p>
  </div>

  <div class="pin-edit__field">
    <label class="pin-edit__label" for="description">Tell us about yourself or your place.</label>
    <textarea class="pin-edit__input" id="description" name="description" maxlength="200"><?php echo $record->description; ?></textarea>
  </div>

  <div class="pin-edit__field">
    <label class="pin-edit__label" for="website">Have you got an account on our marketspace where you sell items or a website where people can find you?</label>
    <input class="pin-edit__input" type="text" id="website" name="website" maxlength="200" value="<?php echo $record->website; ?>">
    <p class="pin-edit__error pin-edit__error--website">Website is invalid</p>
  </div>

  <fieldset class="pin-edit__field">
    <legend class="pin-edit__label">Can people drop by and visit your pin?</legend>
    <?php
    foreach ($statuses as $key => $value) {
      $checked = ($key == $record->status || (empty($record->status) && $key == 'OPEN')) ? "checked" : "";
      echo "<div class='pin-edit__choice'>
        <input type='radio' id='$key' name='status' value='$key' $checked>
        <label for='$key'>$value</label>
      </div>";
    }
    ?>
  </fieldset>

  <fieldset class="pin-edit__field">
    <legend class="pin-edit__label">Share some images of your machines, workspace or yourself.</legend>
    <?php 
      for ($x = 0; $x < 3; $x++) {
        $imgs = json_decode($record->imgs, true);
        $img = $imgs[$x][0];
        $style = $img ? "style='background-image: url(\"$img\");'" : "";
        $class = $img ? "pin-edit__upload--active" : "";
        
        echo "<label id='img$x-preview' tabindex='0' role='button' class='pin-edit__upload $class' for='img$x' $style>
          <svg class='pin-edit__upload__icon' x='0px' y='0px' viewBox='0 0 800 700' enable-background='new 0 0 800 800' xml:space='preserve'><g><path d='M751.553,148.242H48.447c-8.254,0-14.947,6.693-14.947,14.947v473.623c0,0.985,0.102,1.963,0.292,2.927   c0.087,0.444,0.255,0.854,0.379,1.284c0.153,0.503,0.27,1.015,0.467,1.503c0.211,0.504,0.496,0.964,0.759,1.431   c0.204,0.38,0.379,0.773,0.62,1.139c1.095,1.649,2.503,3.051,4.138,4.146c0.365,0.24,0.745,0.401,1.109,0.605   c0.489,0.271,0.956,0.563,1.467,0.767c0.489,0.204,0.993,0.321,1.496,0.467c0.431,0.132,0.847,0.292,1.292,0.38   c0.971,0.196,1.949,0.299,2.927,0.299h703.106c0.985,0,1.964-0.103,2.927-0.299c0.438-0.081,0.832-0.248,1.256-0.365   c0.518-0.153,1.036-0.277,1.532-0.481s0.935-0.481,1.401-0.73c0.395-0.219,0.803-0.394,1.175-0.642   c1.643-1.095,3.044-2.504,4.139-4.146c0.248-0.365,0.408-0.759,0.62-1.139c0.255-0.467,0.547-0.927,0.752-1.431   c0.204-0.488,0.32-1,0.467-1.503c0.131-0.431,0.292-0.84,0.38-1.284c0.196-0.964,0.299-1.941,0.299-2.927V163.188   C766.5,154.935,759.808,148.242,751.553,148.242z M84.529,621.864l167.917-167.909l42.213,42.206   c5.838,5.838,15.297,5.838,21.135,0l136.987-136.98l262.69,262.683H84.529z M736.606,600.729L463.348,327.471   c-5.838-5.839-15.304-5.839-21.135,0L305.226,464.45l-42.212-42.205c-5.605-5.605-15.531-5.605-21.136,0L63.393,600.729V178.135   h673.213V600.729z'/><path d='M427.229,422.485l-62.275,62.275c-5.838,5.831-5.838,15.305,0,21.136c2.919,2.919,6.744,4.379,10.568,4.379   s7.648-1.46,10.568-4.379l62.275-62.275c5.838-5.839,5.838-15.297,0-21.136C442.533,416.647,433.061,416.647,427.229,422.485z'/><path d='M231.208,512.939l-58.539,58.546c-5.838,5.839-5.838,15.297,0,21.136c2.919,2.919,6.744,4.379,10.568,4.379   s7.656-1.46,10.568-4.379l58.539-58.546c5.838-5.839,5.838-15.298,0-21.136C246.504,507.101,237.046,507.101,231.208,512.939z'/><path d='M163.349,350.022c35.71,0,64.764-29.054,64.764-64.764s-29.054-64.771-64.764-64.771   c-35.717,0-64.771,29.062-64.771,64.771S127.632,350.022,163.349,350.022z M163.349,250.38c19.224,0,34.871,15.647,34.871,34.878   c0,19.231-15.647,34.871-34.871,34.871c-19.238,0-34.878-15.64-34.878-34.871C128.471,266.027,144.111,250.38,163.349,250.38z'/></g></svg>
          <p class='pin-edit__upload__desc'>Upload</p>
          <input class='pin-edit__upload__input' type='file' name='img$x-file' id='img$x' accept='image/*'/>
        </label>";
      }
    ?>
  </fieldset>

  <input type="submit" name="submit" value="Save Pin" class="pin-edit__btn pin-center button button-primary"/>
</form>
