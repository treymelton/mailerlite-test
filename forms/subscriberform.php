<?php

$strSubscriberForm = '<!-- row one -->
                <div class="row rowmarginoffset">
                  <div class="col-lg-6 form-group  required">
                    <label for="subscribername" class="control-label">Name:</label>
                    <input type="text" class="form-control" id="subscribername" name="subscribername" placeholder="Name" required value="'. $arrPOST['subscribername'].'" />
                  </div>
                  <div class="col-lg-6 form-group  required">
                    <label for="subscriberemail" class="control-label">Email:</label>
                    <input type="text" class="form-control" id="subscriberemail" name="subscriberemail" placeholder="Email" required value="'. $arrPOST['subscriberemail'].'" />
                  </div>
                </div>
                <!-- /row one -->
                <!-- row two -->
                <div class="row rowmarginoffset">
                  <div class="col-lg-6 form-group">
                    <label for="subscriberstate">State:</label>
                    <select name="subscriberstate" id="subscriberstate" class="form-control" >
                    <option value="">Select</option>';
$strSubscriberForm .= Abstraction::Get()->MakeSimpleDropDownOptions(Utility::Get()->MakeStatesArray(),$arrPOST['subscriberstate']);
$strSubscriberForm .= '</select>
                  </div>
                  <div class="col-lg-6 form-group">
                    <label for="subscriberzip">Zip:</label>
                    <input type="text" class="form-control" id="subscriberzip" name="subscriberzip" placeholder="Zip" value="'.$arrPOST['subscriberzip'].'" />
                  </div>
                </div>
                <!-- /row two -->
                <!-- row three -->
                <div class="row rowmarginoffset">
                  <div class="col-lg-6 form-group">
                    <label for="subscribercountry">Country:</label>
                    <select name="subscribercountry" id="subscribercountry" class="form-control" >
                    <option value="">Select</option>';
$strSubscriberForm .=Abstraction::Get()->MakeSimpleDropDownOptions(Utility::Get()->MakeCountryArray(),$arrPOST['subscribercountry']);
$strSubscriberForm .= '</select>
                  </div>
                  <div class="col-lg-6 form-group  required">
                    <label for="subscriberstatus" class="control-label">Subscription Status:</label>
                    <select name="subscriberstatus" id="subscriberstatus" class="form-control" required >
                    <option value="">Select</option>';
$strSubscriberForm .=Abstraction::Get()->MakeSimpleDropDownOptions(Utility::Get()->CreateStatusArray(),$arrPOST['subscriberstatus']);
$strSubscriberForm .= '</select>
                  </div>
                </div>
                <!-- /row three -->';
?>