<?php

class User_Edit_Form {

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

  function from_JSON($value) {
    return join(',', json_decode($value));
  }

  function get_columns() {
    return array(
      'name' => array('Name', true),
      'lat' => array('Latitude', true),
      'lng' => array('Longitude', true),
      'address' => array('Address', true),
      'description' => array('Description', true),
      'show_on_map' => array('Approved for Display', false),
      'filters' => array('Filters', true, 'from_JSON')
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
    $rows = $this->get_columns();

    $query = "SELECT " . join(',', array_keys($rows)) . " FROM pp_pins WHERE ID = " . $recordId . ' AND user_ID = ' . get_current_user_id();

    $this->record = $wpdb->get_results($query)[0];
  }

  function display() {
    $record = $this->record;
    $rows = $this->get_columns();

    foreach ($rows as $key => $value) {
      echo '<div>';
      echo '<div class="form-label"><label for="' . $key . '">' . $value[0] . '</label></div>';
      echo $this->getValueRow($key, $value, $record);
      echo '</div>';
    }

    echo '</table>';
  }

  function getValueRow($key, $value, $record) {
    $recordValue = $record->$key != null ? $record->$key : null;
    $recordValue = htmlspecialchars(empty($value[2]) ? $recordValue : $this->{$value[2]}($recordValue));
    echo '<div class="form-value">';
    if ($value[1]) {
      echo '<input name="' . $key . '" class="regular-text" value="' . $recordValue . '"/>';
    } else {
      echo $recordValue;
    }
    echo '</div>';
  }
}

$table = new User_Edit_Form();
$table->prepare_items();
$record = $table->get_record();
?>

<form class="pin-edit" method="POST" action="<?php echo $url=strtok($_SERVER["REQUEST_URI"],'?'); ?>">
  <input type="hidden" name="action" value="edit_pin" />
  <input type="hidden" name="_wpnonce" value="<?php echo $table->get_edit_nonce(); ?>" />
  <input type="hidden" name="id" value="<?php echo $table->get_record_id(); ?>" />

  <div class="pin-edit__field">
    <label class="pin-edit__label" for="address">Type your pin address, so we can locate it on the map.</label> 
    <input class="pin-edit__input" type="text" id="address" name="address" placeholder="Amsterdam, The Netherlands" maxlength="200" value="<?php echo $record->address; ?>">
  </div>

  <fieldset class="pin-edit__field">
    <legend class="pin-edit__label">How are you involved with Precious Plastic?</legend>
    <div class="pin-edit__choice">
      <input type="checkbox" id="workshop" name="filter" value="workshop">
      <label for="workshop">I have a Precious Plastic workspace</label>
    </div>
    <div class="pin-edit__choice">
      <input type="checkbox" id="machine" name="filter" value="machine">
      <label for="machine">I sell machines / can build machines for others</label>
    </div>
    <div class="pin-edit__choice">
      <input type="checkbox" id="started" name="filter" value="started">
      <label for="started">I want to find people locally to help me get started</label>
    </div>
  </fieldset>

  <div class="pin-edit__field">
    <label class="pin-edit__label" for="name">What's the name of your pin?</label> 
    <input class="pin-edit__input" type="text" id="name" name="name" placeholder="Talulah's Workshop" maxlength="200" value="<?php echo $record->name; ?>">
  </div>

  <div class="pin-edit__field">
    <label class="pin-edit__label" for="description">Tell us about yourself or your place.</label> 
    <textarea class="pin-edit__input" id="description" name="description" maxlength="200" value="<?php echo $record->description; ?>"></textarea>
  </div>

  <div class="pin-edit__field">
    <label class="pin-edit__label" for="website">Have you got an account on our marketspace where you sell items or a website where people can find you?</label> 
    <input class="pin-edit__input" type="text" id="website" name="website" maxlength="200" value="<?php echo $record->website; ?>">
  </div>

  <fieldset class="pin-edit__field">
    <legend class="pin-edit__label">Can people drop by and visit your pin?</legend>
    <div class="pin-edit__choice">
      <input type="radio" id="yes" name="status" value="yes">
      <label for="yes">Yes, of course!</label>
    </div>
    <div class="pin-edit__choice">
      <input type="radio" id="no" name="status" value="no">
      <label for="no">No, I am shy :)</label>
    </div>
  </fieldset>

  <fieldset class="pin-edit__field">
    <legend class="pin-edit__label">Share some images of your machines, workspace or yourself.</legend>

    <label id="img1-preview" class="pin-edit__upload" for="img1">
      <svg class="pin-edit__upload__icon" x="0px" y="0px" viewBox="0 0 800 700" enable-background="new 0 0 800 800" xml:space="preserve"><g><path d="M751.553,148.242H48.447c-8.254,0-14.947,6.693-14.947,14.947v473.623c0,0.985,0.102,1.963,0.292,2.927   c0.087,0.444,0.255,0.854,0.379,1.284c0.153,0.503,0.27,1.015,0.467,1.503c0.211,0.504,0.496,0.964,0.759,1.431   c0.204,0.38,0.379,0.773,0.62,1.139c1.095,1.649,2.503,3.051,4.138,4.146c0.365,0.24,0.745,0.401,1.109,0.605   c0.489,0.271,0.956,0.563,1.467,0.767c0.489,0.204,0.993,0.321,1.496,0.467c0.431,0.132,0.847,0.292,1.292,0.38   c0.971,0.196,1.949,0.299,2.927,0.299h703.106c0.985,0,1.964-0.103,2.927-0.299c0.438-0.081,0.832-0.248,1.256-0.365   c0.518-0.153,1.036-0.277,1.532-0.481s0.935-0.481,1.401-0.73c0.395-0.219,0.803-0.394,1.175-0.642   c1.643-1.095,3.044-2.504,4.139-4.146c0.248-0.365,0.408-0.759,0.62-1.139c0.255-0.467,0.547-0.927,0.752-1.431   c0.204-0.488,0.32-1,0.467-1.503c0.131-0.431,0.292-0.84,0.38-1.284c0.196-0.964,0.299-1.941,0.299-2.927V163.188   C766.5,154.935,759.808,148.242,751.553,148.242z M84.529,621.864l167.917-167.909l42.213,42.206   c5.838,5.838,15.297,5.838,21.135,0l136.987-136.98l262.69,262.683H84.529z M736.606,600.729L463.348,327.471   c-5.838-5.839-15.304-5.839-21.135,0L305.226,464.45l-42.212-42.205c-5.605-5.605-15.531-5.605-21.136,0L63.393,600.729V178.135   h673.213V600.729z"/><path d="M427.229,422.485l-62.275,62.275c-5.838,5.831-5.838,15.305,0,21.136c2.919,2.919,6.744,4.379,10.568,4.379   s7.648-1.46,10.568-4.379l62.275-62.275c5.838-5.839,5.838-15.297,0-21.136C442.533,416.647,433.061,416.647,427.229,422.485z"/><path d="M231.208,512.939l-58.539,58.546c-5.838,5.839-5.838,15.297,0,21.136c2.919,2.919,6.744,4.379,10.568,4.379   s7.656-1.46,10.568-4.379l58.539-58.546c5.838-5.839,5.838-15.298,0-21.136C246.504,507.101,237.046,507.101,231.208,512.939z"/><path d="M163.349,350.022c35.71,0,64.764-29.054,64.764-64.764s-29.054-64.771-64.764-64.771   c-35.717,0-64.771,29.062-64.771,64.771S127.632,350.022,163.349,350.022z M163.349,250.38c19.224,0,34.871,15.647,34.871,34.878   c0,19.231-15.647,34.871-34.871,34.871c-19.238,0-34.878-15.64-34.878-34.871C128.471,266.027,144.111,250.38,163.349,250.38z"/></g></svg>
      <p class="pin-edit__upload__desc">Upload</p>
      <input class="pin-edit__upload__input" type="file" name="img1" id="img1" accept="image/*"/>
    </label>

    <label id="img2-preview" class="pin-edit__upload" for="img2">
      <svg class="pin-edit__upload__icon" x="0px" y="0px" viewBox="0 0 800 700" enable-background="new 0 0 800 800" xml:space="preserve"><g><path d="M751.553,148.242H48.447c-8.254,0-14.947,6.693-14.947,14.947v473.623c0,0.985,0.102,1.963,0.292,2.927   c0.087,0.444,0.255,0.854,0.379,1.284c0.153,0.503,0.27,1.015,0.467,1.503c0.211,0.504,0.496,0.964,0.759,1.431   c0.204,0.38,0.379,0.773,0.62,1.139c1.095,1.649,2.503,3.051,4.138,4.146c0.365,0.24,0.745,0.401,1.109,0.605   c0.489,0.271,0.956,0.563,1.467,0.767c0.489,0.204,0.993,0.321,1.496,0.467c0.431,0.132,0.847,0.292,1.292,0.38   c0.971,0.196,1.949,0.299,2.927,0.299h703.106c0.985,0,1.964-0.103,2.927-0.299c0.438-0.081,0.832-0.248,1.256-0.365   c0.518-0.153,1.036-0.277,1.532-0.481s0.935-0.481,1.401-0.73c0.395-0.219,0.803-0.394,1.175-0.642   c1.643-1.095,3.044-2.504,4.139-4.146c0.248-0.365,0.408-0.759,0.62-1.139c0.255-0.467,0.547-0.927,0.752-1.431   c0.204-0.488,0.32-1,0.467-1.503c0.131-0.431,0.292-0.84,0.38-1.284c0.196-0.964,0.299-1.941,0.299-2.927V163.188   C766.5,154.935,759.808,148.242,751.553,148.242z M84.529,621.864l167.917-167.909l42.213,42.206   c5.838,5.838,15.297,5.838,21.135,0l136.987-136.98l262.69,262.683H84.529z M736.606,600.729L463.348,327.471   c-5.838-5.839-15.304-5.839-21.135,0L305.226,464.45l-42.212-42.205c-5.605-5.605-15.531-5.605-21.136,0L63.393,600.729V178.135   h673.213V600.729z"/><path d="M427.229,422.485l-62.275,62.275c-5.838,5.831-5.838,15.305,0,21.136c2.919,2.919,6.744,4.379,10.568,4.379   s7.648-1.46,10.568-4.379l62.275-62.275c5.838-5.839,5.838-15.297,0-21.136C442.533,416.647,433.061,416.647,427.229,422.485z"/><path d="M231.208,512.939l-58.539,58.546c-5.838,5.839-5.838,15.297,0,21.136c2.919,2.919,6.744,4.379,10.568,4.379   s7.656-1.46,10.568-4.379l58.539-58.546c5.838-5.839,5.838-15.298,0-21.136C246.504,507.101,237.046,507.101,231.208,512.939z"/><path d="M163.349,350.022c35.71,0,64.764-29.054,64.764-64.764s-29.054-64.771-64.764-64.771   c-35.717,0-64.771,29.062-64.771,64.771S127.632,350.022,163.349,350.022z M163.349,250.38c19.224,0,34.871,15.647,34.871,34.878   c0,19.231-15.647,34.871-34.871,34.871c-19.238,0-34.878-15.64-34.878-34.871C128.471,266.027,144.111,250.38,163.349,250.38z"/></g></svg>
      <p class="pin-edit__upload__desc">Upload</p>
      <input class="pin-edit__upload__input" type="file" name="img2" id="img2" accept="image/*"/>
    </label>
    
    <label id="img3-preview" class="pin-edit__upload" for="img3">
      <svg class="pin-edit__upload__icon" x="0px" y="0px" viewBox="0 0 800 700" enable-background="new 0 0 800 800" xml:space="preserve"><g><path d="M751.553,148.242H48.447c-8.254,0-14.947,6.693-14.947,14.947v473.623c0,0.985,0.102,1.963,0.292,2.927   c0.087,0.444,0.255,0.854,0.379,1.284c0.153,0.503,0.27,1.015,0.467,1.503c0.211,0.504,0.496,0.964,0.759,1.431   c0.204,0.38,0.379,0.773,0.62,1.139c1.095,1.649,2.503,3.051,4.138,4.146c0.365,0.24,0.745,0.401,1.109,0.605   c0.489,0.271,0.956,0.563,1.467,0.767c0.489,0.204,0.993,0.321,1.496,0.467c0.431,0.132,0.847,0.292,1.292,0.38   c0.971,0.196,1.949,0.299,2.927,0.299h703.106c0.985,0,1.964-0.103,2.927-0.299c0.438-0.081,0.832-0.248,1.256-0.365   c0.518-0.153,1.036-0.277,1.532-0.481s0.935-0.481,1.401-0.73c0.395-0.219,0.803-0.394,1.175-0.642   c1.643-1.095,3.044-2.504,4.139-4.146c0.248-0.365,0.408-0.759,0.62-1.139c0.255-0.467,0.547-0.927,0.752-1.431   c0.204-0.488,0.32-1,0.467-1.503c0.131-0.431,0.292-0.84,0.38-1.284c0.196-0.964,0.299-1.941,0.299-2.927V163.188   C766.5,154.935,759.808,148.242,751.553,148.242z M84.529,621.864l167.917-167.909l42.213,42.206   c5.838,5.838,15.297,5.838,21.135,0l136.987-136.98l262.69,262.683H84.529z M736.606,600.729L463.348,327.471   c-5.838-5.839-15.304-5.839-21.135,0L305.226,464.45l-42.212-42.205c-5.605-5.605-15.531-5.605-21.136,0L63.393,600.729V178.135   h673.213V600.729z"/><path d="M427.229,422.485l-62.275,62.275c-5.838,5.831-5.838,15.305,0,21.136c2.919,2.919,6.744,4.379,10.568,4.379   s7.648-1.46,10.568-4.379l62.275-62.275c5.838-5.839,5.838-15.297,0-21.136C442.533,416.647,433.061,416.647,427.229,422.485z"/><path d="M231.208,512.939l-58.539,58.546c-5.838,5.839-5.838,15.297,0,21.136c2.919,2.919,6.744,4.379,10.568,4.379   s7.656-1.46,10.568-4.379l58.539-58.546c5.838-5.839,5.838-15.298,0-21.136C246.504,507.101,237.046,507.101,231.208,512.939z"/><path d="M163.349,350.022c35.71,0,64.764-29.054,64.764-64.764s-29.054-64.771-64.764-64.771   c-35.717,0-64.771,29.062-64.771,64.771S127.632,350.022,163.349,350.022z M163.349,250.38c19.224,0,34.871,15.647,34.871,34.878   c0,19.231-15.647,34.871-34.871,34.871c-19.238,0-34.878-15.64-34.878-34.871C128.471,266.027,144.111,250.38,163.349,250.38z"/></g></svg>
      <p class="pin-edit__upload__desc">Upload</p>
      <input class="pin-edit__upload__input" type="file" name="img3" id="img3" accept="image/*"/>
    </label>

  </fieldset>

  <input type="submit" name="submit" value="Create Pin" class="pin-center button button-primary"/>
</form>
