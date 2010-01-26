<?php 
/**
 * Reports submit view page.
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Ushahidi Team <team@ushahidi.com> 
 * @package    Ushahidi - http://source.ushahididev.com
 * @module     API Controller
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */
?>
		<div id="content">
			<div class="content-bg">
				<!-- start report form block -->
				<?php print form::open(NULL, array('enctype' => 'multipart/form-data', 'id' => 'reportForm', 'name' => 'reportForm')); ?>
				<input type="hidden" name="latitude" id="latitude" value="<?php echo $form['latitude']; ?>">
				<input type="hidden" name="longitude" id="longitude" value="<?php echo $form['longitude']; ?>">
				<div class="big-block">
					<div class="big-block-top">
						<div class="big-block-bottom">
							<h1><?php echo Kohana::lang('ui_main.reports_submit_new'); ?></h1>
							<?php
							if ($form_error) {
							?>
								<!-- red-box -->
								<div class="red-box">
									<h3>Error!</h3>
									<ul>
									<?php
									foreach ($errors as $error_item => $error_description)
									{
										// print "<li>" . $error_description . "</li>";
										print (!$error_description) ? '' : "<li>" . $error_description . "</li>";
									}
									?>
									</ul>
								</div>
							<?php
							}
							?>
                                                        <div class="report_left">
                                                    <div class="row">
						        <h4>Form <span>(Select A Form Type)</span></h4>
						        <span class="sel-holder">
							    <?php print form::dropdown('form_id', $forms, $form['form_id'],
										' onchange="formSwitch(this.options[this.selectedIndex].value, \''.$id.'\')"') ?>
						        </span>
							<div id="form_loader" style="float:left;"></div>
						    </div>

		                    	<div class="report_row">
		                        	<h4><?php echo Kohana::lang('ui_main.reports_title'); ?></h4>
									<?php print form::input('incident_title', $form['incident_title'], ' class="text long"'); ?>
		                        </div>
		                        <div class="report_row">
		                        	<h4><?php echo Kohana::lang('ui_main.reports_description'); ?></h4>
									<?php print form::textarea('incident_description', $form['incident_description'], ' rows="10" class="textarea long" ') ?>
		                        </div>
								<div class="report_row" id="datetime_default">
									<!-- <h4><a href="#" id="date_toggle" class="show-more">Modify Date</a>Date & Time:
									<?php echo "Today at".$form['incident_hour']
										.":".$form['incident_minute']." ".$form['incident_ampm']; ?></h4> -->
								</div>
		                        <div class="report_row hide" id="datetime_edit">
		                       	  <div class="date-box">
		                            	<h4><?php echo Kohana::lang('ui_main.reports_date'); ?></h4>
										<?php print form::input('incident_date', $form['incident_date'], ' class="text short"'); ?>								
										<script type="text/javascript">
											$().ready(function() {
												$("#incident_date").datepicker({ 
												    showOn: "both", 
												    buttonImage: "<?php echo url::base() ?>media/img/icon-calendar.gif", 
												    buttonImageOnly: true 
												});
											});
									    </script>
		                          </div> 
		                          <div class="time">
		                            	<h4><?php echo Kohana::lang('ui_main.reports_time'); ?></h4>
						    		  	<?php
										for ($i=1; $i <= 12 ; $i++) { 
											$hour_array[sprintf("%02d", $i)] = sprintf("%02d", $i); 	// Add Leading Zero
										}
										for ($j=0; $j <= 59 ; $j++) { 
											$minute_array[sprintf("%02d", $j)] = sprintf("%02d", $j);	// Add Leading Zero
										}
										$ampm_array = array('pm'=>'pm','am'=>'am');
										print form::dropdown('incident_hour',$hour_array,$form['incident_hour']);
										print '<span class="dots">:</span>';
										print form::dropdown('incident_minute',$minute_array,$form['incident_minute']);
										print '<span class="dots">:</span>';
										print form::dropdown('incident_ampm',$ampm_array,$form['incident_ampm']);
										?>
		                          </div>
		                          <div style="clear:both; display:block;" id="incident_date_time"></div>
		                        </div>
		                        <div class="report_row">
		                        	<h4><?php echo Kohana::lang('ui_main.reports_categories'); ?></h4>
							    	<div class="report_category" id="categories">
										                                        
                                        <?php
                                        //format categories for 2 column display
                                        $this_col = 1; // First column
		                                $maxper_col = round($categories_total/2); // Maximum number of elements per column
										
										$i = 1; // Element Count
                                        foreach ($categories as $category => $category_extra)
                                        {
                                            $category_title = $category_extra[0];
                                            $category_color = $category_extra[1];
                                            if ($this_col == 1) 
                                                echo "<ul>";
                                        
                                            if (!empty($selected_categories) 
                                                && in_array($category, $selected_categories)) {
                                                $category_checked = TRUE;
                                            }
                                            else
                                            {
                                                $category_checked = FALSE;
                                            }
                                                                                                            
                                            echo "\n<li><label>";
                                            echo form::checkbox('incident_category[]', $category, $category_checked, ' class="check-box"');
                                            echo "$category_title";
                                            echo "</label></li>";
                                    
                                            if ($this_col == $maxper_col || $i == count($categories)) 
		                                        print "</ul>\n";

		                                    if ($this_col < $maxper_col)
		                                    {
		                                        $this_col++;
		                                    } 
		                                    else 
		                                    {
		                                        $this_col = 1;
		                                    }
											$i++;
                                        }

                                        ?>
                                       
									</div>
                                        </div>
                                        <div id="custom_forms">
                                            	<?php
								foreach ($disp_custom_fields as $field_id => $field_property)
								{
									echo "<div class=\"row\">";
									echo "<h4>" . $field_property['field_name'] . "</h4>";
									if ($field_property['field_type'] == 1)
									{ // Text Field
										// Is this a date field?
										if ($field_property['field_isdate'] == 1)
										{
											echo form::input('custom_field['.$field_id.']', $form['custom_field'][$field_id],
												' id="custom_field_'.$field_id.'" class="text"');
											echo "<script type=\"text/javascript\">
													$(document).ready(function() {
													$(\"#custom_field_".$field_id."\").datepicker({ 
													showOn: \"both\", 
													buttonImage: \"" . url::base() . "media/img/icon-calendar.gif\", 
													buttonImageOnly: true 
													});
													});
												</script>";
										}
										else
										{
											echo form::input('custom_field['.$field_id.']', $form['custom_field'][$field_id],
												' id="custom_field_'.$field_id.'" class="text custom_text"');
										}
									}
									elseif ($field_property['field_type'] == 2)
									{ // TextArea Field
										echo form::textarea('custom_field['.$field_id.']', $form['custom_field'][$field_id], ' class="custom_text" rows="3"');
									}
									echo "</div>";
								}
								?>

                                        </div>
								
								<div class="report_optional">
									<h3><?php echo Kohana::lang('ui_main.reports_optional'); ?></h3>
		                        	<div class="report_row">
			                        	<h4><?php echo Kohana::lang('ui_main.reports_first'); ?></h4>
										<?php print form::input('person_first', $form['person_first'], ' class="text long"'); ?>
			                        </div>
			                        <div class="report_row">
			                        	<h4><?php echo Kohana::lang('ui_main.reports_last'); ?></h4>
										<?php print form::input('person_last', $form['person_last'], ' class="text long"'); ?>
			                        </div>
			                        <div class="report_row">
			                        	<h4><?php echo Kohana::lang('ui_main.reports_email'); ?></h4>
										<?php print form::input('person_email', $form['person_email'], ' class="text long"'); ?>
			                        </div>
								</div>
							</div>
							
		               	  	<div class="report_right">
		                    	<?php if (!$multi_country)
								{
		                    		?>
									<div class="report_row">
			                        	<h4><?php echo Kohana::lang('ui_main.reports_find_location'); ?></h4>
			                            <?php print form::dropdown('select_city',$cities,'', ' class="select" '); ?>
			                    	</div>
									<?php
		                    	}?>
		                        <div class="report_row">
		                        	<div id="divMap" class="report_map"></div>
									<div class="report-find-location">
										<?php print form::input('location_find', '', ' title="City, State and/or Country" class="findtext"'); ?>
										<div style="float:left;margin:9px 0 0 5px;"><input type="button" name="button" id="button" value="Find Location" class="btn_gray btn_find" /></div>
										<div id="find_loading" class="report-find-loading"></div>
										<div style="clear:both;" id="find_text">* If you can't find your location, please click on the map to pinpoint the correct location.</div>
									</div>
		                        </div>
								
		                        <div class="report_row">
		                        	<h4><?php echo Kohana::lang('ui_main.reports_location_name'); ?><br /><span>Examples: Johannesburg, Corner City Market, 5th Street & 4th Avenue</span></h4>
									<?php print form::input('location_name', $form['location_name'], ' class="text long"'); ?>
		                        </div>

								<!-- News Fields -->
								<div id="divNews" class="report_row">
			                        <h4><?php echo Kohana::lang('ui_main.reports_news'); ?></h4>
									<?php
									$this_div = "divNews";
									$this_field = "incident_news";
									$this_startid = "news_id";
									$this_field_type = "text";

									if (empty($form[$this_field]))
									{
										$i = 1;
										print "<div class=\"report_row\">";
										print form::input($this_field . '[]', '', ' class="text long2"');
										print "<a href=\"#\" class=\"add\" onClick=\"addFormField('$this_div','$this_field','$this_startid','$this_field_type'); return false;\">add</a>";
										print "</div>";
									}
									else
									{
										$i = 0;
										foreach ($form[$this_field] as $value) {									
											print "<div class=\"report_row\" id=\"$i\">\n";
											
											print form::input($this_field . '[]', $value, ' class="text long2"');
											print "<a href=\"#\" class=\"add\" onClick=\"addFormField('$this_div','$this_field','$this_startid','$this_field_type'); return false;\">add</a>";
											if ($i != 0)
											{
												print "<a href=\"#\" class=\"rem\"  onClick='removeFormField(\"#" . $this_field . "_" . $i . "\"); return false;'>remove</a>";
											}
											print "</div>\n";
											$i++;
										}
									}
									print "<input type=\"hidden\" name=\"$this_startid\" value=\"$i\" id=\"$this_startid\">";
									?>
								</div>


								<!-- Video Fields -->
								<div id="divVideo" class="report_row">
									<h4><?php echo Kohana::lang('ui_main.reports_video'); ?></h4>
									<?php
									$this_div = "divVideo";
									$this_field = "incident_video";
									$this_startid = "video_id";
									$this_field_type = "text";

									if (empty($form[$this_field]))
									{
										$i = 1;
										print "<div class=\"report_row\">";
										print form::input($this_field . '[]', '', ' class="text long2"');
										print "<a href=\"#\" class=\"add\" onClick=\"addFormField('$this_div','$this_field','$this_startid','$this_field_type'); return false;\">add</a>";
										print "</div>";
									}
									else
									{
										$i = 0;
										foreach ($form[$this_field] as $value) {									
											print "<div class=\"report_row\" id=\"$i\">\n";
											
											print form::input($this_field . '[]', $value, ' class="text long2"');
											print "<a href=\"#\" class=\"add\" onClick=\"addFormField('$this_div','$this_field','$this_startid','$this_field_type'); return false;\">add</a>";
											if ($i != 0)
											{
												print "<a href=\"#\" class=\"rem\"  onClick='removeFormField(\"#" . $this_field . "_" . $i . "\"); return false;'>remove</a>";
											}
											print "</div>\n";
											$i++;
										}
									}
									print "<input type=\"hidden\" name=\"$this_startid\" value=\"$i\" id=\"$this_startid\">";
									?>
								</div>
								
								
								<!-- Photo Fields -->
								<div id="divPhoto" class="report_row">
									<h4><?php echo Kohana::lang('ui_main.reports_photos'); ?></h4>
									<?php
									$this_div = "divPhoto";
									$this_field = "incident_photo";
									$this_startid = "photo_id";
									$this_field_type = "file";

									if (empty($form[$this_field]['name'][0]))
									{
										$i = 1;
										print "<div class=\"report_row\">";
										print form::upload($this_field . '[]', '', ' class="file long2"');
										print "<a href=\"#\" class=\"add\" onClick=\"addFormField('$this_div','$this_field','$this_startid','$this_field_type'); return false;\">add</a>";
										print "</div>";
									}
									else
									{
										$i = 0;
										foreach ($form[$this_field]['name'] as $value) 
										{
											print "<div class=\"report_row\" id=\"$i\">\n";
											
											// print "\"<strong>" . $value . "</strong>\"" . "<BR />";
											print form::upload($this_field . '[]', $value, ' class="file long2"');
											print "<a href=\"#\" class=\"add\" onClick=\"addFormField('$this_div','$this_field','$this_startid','$this_field_type'); return false;\">add</a>";
											if ($i != 0)
											{
												print "<a href=\"#\" class=\"rem\"  onClick='removeFormField(\"#" . $this_field . "_" . $i . "\"); return false;'>remove</a>";
											}
											print "</div>\n";
											$i++;
										}
									}
									print "<input type=\"hidden\" name=\"$this_startid\" value=\"$i\" id=\"$this_startid\">";
									?>
                                                                </div>
                                                                 										
                                                        <div style="clear:both;"></div>
                                                         <div id="divDoc" class="report_row">
                                                                    <h4>Upload Docs</h4>
								<?php
								$this_div = "divDoc";
								$this_field = "incident_doc";
								$this_startid = "doc_id";
								$this_field_type = "file";
					
								if (empty($form[$this_field]['name'][0]))
								{
									$i = 1;
									print "<div class=\"report-row\">";
									print form::upload($this_field . '[]', '', ' class="file long2"');
									print "<a href=\"#\" class=\"add\" onClick=\"addFormField('$this_div','$this_field','$this_startid','$this_field_type'); return false;\">add</a>";
									print "</div>";
								}
								else
								{
									$i = 0;
									foreach ($form[$this_field]['name'] as $value) 
									{
										print "<div class=\"report_row\" id=\"$i\">\n";
											
											// print "\"<strong>" . $value . "</strong>\"" . "<BR />";
											print form::upload($this_field . '[]', $value, ' class="file long2"');
											print "<a href=\"#\" class=\"add\" onClick=\"addFormField('$this_div','$this_field','$this_startid','$this_field_type'); return false;\">add</a>";
											if ($i != 0)
											{
												print "<a href=\"#\" class=\"rem\"  onClick='removeFormField(\"#" . $this_field . "_" . $i . "\"); return false;'>remove</a>";
											}
											print "</div>\n";
											$i++;
									}
								}
								print "<input type=\"hidden\" name=\"$this_startid\" value=\"$i\" id=\"$this_startid\">";
								?>
                                                        </div>


		                  	</div>
		                    <div class="report_bottom">
		                        <div class="report_row">
		                        	<input name="submit" type="submit" value="<?php echo Kohana::lang('ui_main.reports_btn_submit'); ?>" class="btn_blue" />
		                        </div>
		                    </div>
					  </div>
					</div>
				</div>
				<?php print form::close(); ?>
				<!-- end report form block -->
			</div>
		</div>