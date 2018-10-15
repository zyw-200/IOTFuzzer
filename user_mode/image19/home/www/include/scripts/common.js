/*****************
	* Frames related functions....
	*/
var modesList0;
var modesList1;
var modesList;
var numModes0;
var numModes1;

var columntype="";
var defaultsetting="";

var formObject = Class.create();
formObject.prototype = {
	tab1: null,
	tab2: null,
    activeMode: 123,
    activeTab: 0,
	initialize: function() {
		if (config.TWOGHZ.status)
			this.tab1 = new tabObject('1');
		if (config.FIVEGHZ.status)
			this.tab2 = new tabObject('2');
	}
}
var tabObject = Class.create();
tabObject.prototype = {
	modesList0:'',
	modesList1:'',
	id: 1,
	tabIdentifier: 1,
	activeModeField: null,
	activeMode: '',
	currentMode: null,
	currentTab: '',
	radioId: '',
	activateTab: false,
	wdsTabCount: 1,
    modeString: '',
	initialize: function(id) {
		if(!id) throw new Error("Javascript::initialize - No id has been provided!");
		else this.id = parseInt(id);
		this.modesList0 = modesList0;
		this.modesList1 = modesList1;
		this.modesList = modesList;
		this.numModes0 = numModes0;
		this.numModes1 = numModes1;
		this.tabIdentifier = this.id;
		this.tabBlock = $('wlan'+id);
		this.tabItem = $('inlineTab'+id).firstDescendant();
		this.form = this.tabItem.form;
        this.currentTab = (this.id != 1)? '1':'0';
		this.currentMode = $('modeWlan'+(parseInt(id)-1));
		this.getActiveMode();
		this.getWdsTabCount();
		this.radioId = 'WirelessMode1';
		if (this.tabItem!=undefined) {
			Event.observe(this.tabItem, 'click', this.activate.bindAsEventListener(this));
		}
        if (config.DUAL_CONCURRENT.status && this.id != 1)
			this.radioId = 'WirelessMode2';
	},
	getActiveMode: function() {
        if((config.TWOGHZ.status) || (config.FIVEGHZ.status)){
            this.activeModeField=$('activeMode'+this.currentTab);
        }else {
           this.activeModeField=$('activeMode');
       }
		this.activeMode = this.activeModeField.value;
	},
	activate: function(event) {
        //This function gets called when a tab 802.11b/bg/ng or 802.11 a/na is clicked on a page.
        //It sets appropriate page and tab contents.
	      
                if(config.FIVEGHZ.status && this.id != '1' && (($('supports5GHz') != undefined) && ($('supports5GHz').value != 'true'))) {
			    showMessage('5GHz operation not supported for this country!');
		  }
                else {
			this.activateTab = true;
			if (config.FIVEGHZ.status) {
				hideMessage('5GHz operation not supported for this country!');
			}
                      
            if (!config.DUAL_CONCURRENT.status)
                this.getActiveMode();
			if ($('cb_chkRadio'+this.currentTab)!= undefined) {
                //Called for BasicWireless settings page.
				this.setPageContents();
			this.toggleDisplay(this.tabBlock.id);
                            if(config.SCH_WIRELESS_ON_OFF.status){
                                if(($('sch_Stat') != undefined) && ($('sch_Stat').value == 1)){
                                    $('cb_chkRadio'+this.currentTab).disabled = true;
                                }
			   }
                            if(config.WN604.status){
                                if($('rfSwitchStatus' + this.currentTab) != undefined){
                                    var rfStatus = $('rfSwitchStatus' + this.currentTab).value;
                                    if(rfStatus == '0'){
                                        enableInputFields($('table_wlan'+(this.currentTab==0?1:2)),false);
                                    }
                                }
                            }

			}
			else {
                //Called for all pages except BasicWireless settings page.
                if(($('errorMessageBlock').style.display != 'none') && ($('br_head').innerHTML != 'Wireless Radio is turned off!')){
                    if(event != undefined)
                        Event.stop(event);
                        //return;
                }

			this.toggleDisplay(this.tabBlock.id);
				this.setTabContents();
				}
			//this.toggleDisplay(this.tabBlock.id);
			//if (Form.findFirstElement(document.dataForm)!= undefined)
			//	Form.focusFirstElement(document.dataForm); 
            form.activeTab = this.tabIdentifier;
			this.activateTab = false;
		}
	//		enablePrimaryFields();
	},

	setPageContents: function() {
		var rad = getCheckedRadioById(this.radioId);
		if (this.activeMode != '') {
//alert("Before tab Switch:"+"\n"+'activeMode = '+this.activeMode+"\nradioId="+this.radioId+"\nselectedRadioId="+$S(this.radioId));
			if (this.radioId != undefined) {
				setRadioCheckById(this.radioId,getDefaultMode((parseInt(this.tabIdentifier) == 2)?'FIVE':'TWO'));
				this.currentMode.value = this.activeMode;
			}
            else{
//alert("Inside tab Switch:"+"\n"+'activeMode = '+this.activeMode+"\nradioId="+this.radioId+"\nselectedRadioId="+$S(this.radioId));
                setRadioCheckById(this.radioId, $S(this.radioId));
            }
		}
		else if ($(this.radioId) != undefined) {
			if (rad == undefined) {
				setRadioCheckById(this.radioId,getDefaultMode((parseInt(this.tabIdentifier) == 2)?'FIVE':'TWO'));
				var rad = getCheckedRadioById(this.radioId);
			}
			this.currentMode.value = rad.value;
		}
//alert("After tab Switch:"+"\n"+'activeMode = '+this.activeMode+"\nradioId="+this.radioId+"\nselectedRadioId="+$S(this.radioId));
		this.setActiveMode(null,false);
		
        if(!config.DUAL_CONCURRENT.status){
            var table = $('table_wlan'+String((this.id == 1)?(this.id+1):(this.id-1)));
            var actMode = $('activeMode'+String((this.id == 1)?(this.id):(this.id-2)));
            if(table != undefined){
                enableInputFields(table,false,["chkRadio","activeMode"]);
            }

            if(actMode != undefined){
                actMode.disabled = true;
                this.activeModeField.disabled =false;
            }
        }
        DispChannelList(this.tabIdentifier, this.currentMode.value);
		enable11nFields((this.currentMode.value=='2' || this.currentMode.value=='4')?'show':'hide',this.tabIdentifier);

            setTimeout("setHelpUrl(" + this.currentTab + "," + this.id + ")",500);
            
	},

	setTabContents: function() {
        
            if ($('formDisabled') == undefined) {
				enableButtons(["refresh","edit"]);
			}
                if(config.DUAL_CONCURRENT.status){
                        enableInputFields($('table_wlan'+this.id),(this.activeMode != ''));
                    //On profile settings page enable/disable the contents and edit button based on
                    //radio status and WDS settings.
                    if($('formTwoGHzDisable') != undefined && (this.id == 1)){
                        if($('formTwoGHzDisable').value == 'true'){
                            enableInputFields($('table_wlan'+this.id),false);
                            disableButtons(["edit"]);
                        }
                        else{
                            enableButtons(["edit"]);
                        }
                    }
                    if($('formFiveGHzDisable') != undefined && (this.id == 2)){
                        if ($('formFiveGHzDisable').value == 'true') {
                            enableInputFields($('table_wlan'+this.id),false);
                            disableButtons(["edit"]);
                        }
                        else{
                            enableButtons(["edit"]);
                        }
                    }
                }
                else if((this.id == 2 && this.activeMode <= 2) || (this.id == 1 && this.activeMode > 2)) {
                        enableInputFields($('table_wlan'+this.id),false);
                }

                if(($('01_wmmApEdcaAifs') != undefined) || ($('policyName_wlan0') != undefined)){
                    if(($('WMMSupport0').value == '0') || ($('ApMode0').value == '5'))
                        enableInputFields($('table_wlan1'),false);
                    }
                if($('11_wmmApEdcaAifs') != undefined || ($('policyName_wlan1') != undefined)){
                    if(($('WMMSupport1').value == '0') || ($('ApMode1').value == '5'))
                        enableInputFields($('table_wlan2'),false);
                }

                if(($('idwmmSupport0') != undefined)){
                    if(($('ApMode0').value == '5'))
                        enableInputFields($('table_wlan1'),false);
                    }
                if($('idwmmSupport1') != undefined){
                    if(($('ApMode1').value == '5'))
                        enableInputFields($('table_wlan2'),false);
                }

                if ($('cb_rogueApDetection'+this.currentTab) != undefined) {
					$('cb_rogueApDetection'+this.currentTab).disabled = false;
					$('rogueApDetection'+this.currentTab).disabled = false;
                    this.elementID = 'rogueApDetection';
					this.toggleFields();
				}
				else if ($('cb_accessControlMode'+this.currentTab) != undefined) {
					$('cb_accessControlMode'+this.currentTab).disabled = false;
					$('accessControlMode'+this.currentTab).disabled = false;
                    this.elementID = 'accessControlMode';
					this.toggleFields();
				}
				else if ($('cb_wdsEnabled'+this.currentTab) != undefined) {
					$('cb_wdsEnabled'+this.currentTab).disabled = false;
					$('wdsEnabled'+this.currentTab).disabled = false;
					this.wdsOnEnable();
				}

                if(($('accessControlMode'+this.currentTab) != undefined)){
                    if(($('ApMode'+this.currentTab).value == '5')){
                        enableInputFields($('table_wlan' + ((this.currentTab == 0)?1:2)),false);
                        disableButtons(["refresh"]);
                    }
                }

			if ($('enableQoS') != undefined && ($('enableQoS').getAttribute('enableForm') == false)) {
					Form.disable(document.dataForm);
            }

        if (this.activeMode == '') {
			enableInputFields($('table_wlan'+this.id),false);
			disableButtons(["refresh","edit"]);
		}
        if(($('iRTSThreshold'+this.currentTab) != undefined)){
            setTimeout("setHelpUrl(" + this.currentTab + "," + this.id + ")",100);
        }

	},

	getWdsTabCount: function() {
		this.wdsTabCount = 1;
		if(config.CLIENT.status)
			this.wdsTabCount++;
		if(config.P2P.status)
			this.wdsTabCount++;
	},
	
	toggleFields: function(elem) {
        if ($A(arguments)[1] != undefined) this.elementID = $A(arguments)[1];
        var elem = $('cb_'+this.elementID + this.currentTab);
		var inputs=fetchAllInputFields();
		inputs.each(function(input) {
			if (input.type=='checkbox' || input.type=='image' || input.type == 'hidden' || (input.id=='addNewMac'+ this.currentTab)) {
				input.disabled=false;
                if (input.type == 'image') {
                    if (input.disabled == false) {
                        input.src = input.src.replace('_off','_on');
                    }
                    else {
                        input.src = input.src.replace('_on','_off');
                    }
                }
			}
			else if (input.id != elem.id) {
				input.disabled = !elem.checked;
			}
		});
	},

	wdsOnEnable: function() {
		var flag = $('cb_wdsEnabled'+this.currentTab).checked;
		var inputs=fetchAllInputFields();
		var apMode = $('apMode' + this.currentTab);
                   
           if(($('apMode' + this.currentTab).value == 5)){
            if(($('errorMessageBlock').style.display != 'none') && ($('br_head').innerHTML == 'Please address the fields highlighted!')){
                $('cb_wdsEnabled'+this.currentTab).checked = true;
               return;
            }
        }

		if (!this.activateTab) {
			if(config.ARIES.status){
				setApMode((flag)?'2':'0', this.currentTab);
			}
			else {
				setApMode((flag)?getDefaultMode():'0', this.currentTab);
			}
            setRadioCheckById('wdsMode'+this.currentTab,1);
            this.activateSubTab();
		}
		if (!flag) {
			if (config.P2P.status) {
				setRadioCheckById('wdsMode'+this.currentTab,((radioValChanged)?2:1),2);
			}
			else {
				setRadioCheckById('wdsMode'+this.currentTab,((radioValChanged)?4:3),4);
			}
			disableButtons(["edit"]);
            if((config.DUAL_CONCURRENT.status)){
                if(($('errorMessageBlock').style.display != 'none') && ($('br_head').innerHTML == 'Bridging cannot be enabled with channel set to Auto!')){
                    activateApply();
                }
                hideMessage();
            }
			this.toggleProfiles((config.P2P.status)?1:config.NO_OF_WDS_VAPS.count);
            if(config.DUAL_CONCURRENT.status){
                if ((eval('typeof(disableWDSonChannel0)') == 'boolean' && ($('cb_wdsEnabled0').checked == true)) || (eval('typeof(disableWDSonChannel1)') == 'boolean') && ($('cb_wdsEnabled1').checked == true))  {
                showMessage('Bridging cannot be enabled with channel set to Auto!');
				disableButtons(["edit"]);
				activateApply(false);
				$RD('wdsMode'+this.currentTab).each (function(input) {
					input.disabled=false;
				});

                }
            }
		}
		else {
            if (!this.activateTab) {
                if (config.P2P.status) {
                    setRadioCheckById('wdsMode'+this.currentTab,((radioValChanged)?2:1),2);
                }
                else {
                    setRadioCheckById('wdsMode'+this.currentTab,((radioValChanged)?4:3),4);
                }
            }
            if(($('errorMessageBlock').style.display != 'none') && ($('br_head').innerHTML == 'Bridging cannot be enabled with channel set to Auto!')){
                activateApply(false);
                disableButtons(["edit"]);
            }
			if (this.activeMode != '') {
				enableInputFields($('table_wlan'+this.id),flag,['wdsEnabled']);
			}
			if (config.CLIENT.status) {
				this.toggleMACCloneAddress();
			}
			if ((eval('typeof(disableWDSonChannel'+this.currentTab+')') == 'boolean') && (apMode.value != 5)) {
				showMessage('Bridging cannot be enabled with channel set to Auto!');
				disableButtons(["edit"]);
				activateApply(false);
				$RD('wdsMode'+this.currentTab).each (function(input) {
					input.disabled=false;
				});
			}
			else if(($('errorMessageBlock').style.display == 'none') && ($('br_head').innerHTML != 'Bridging cannot be enabled with channel set to Auto!')){
                this.toggleProfiles(this.getProfileCount());
				enableButtons(["edit"]);
			}
            else if(($('errorMessageBlock').style.display != 'none') && ($('br_head').innerHTML == 'Wireless Radio is turned off!')){
                this.toggleProfiles(this.getProfileCount());
            }
            this.activateSubTab();
		}

		//this.activateSubTab();
		$('WCAenabled' + this.currentTab).checked = ((apMode.value == 2) || (apMode.value == 3) || (apMode.value == 0));
		$('apMode'+this.currentTab).disabled=false;
		$('cb_wdsEnabled'+this.currentTab).disabled=false;
		$('wdsEnabled'+this.currentTab).disabled=false;
		enablePrimaryFields();
	},

	setApModeWCA: function() {
		//Purpose :- For WDS pages it sets appropriate apMode value when called with the "Enable Wireless Client Association" checkbox
		//           checked condition and the tab index - 0/1
		$RD('wdsMode'+this.currentTab).each(function(radio) {
			var currentTab = radio.id.substr((radio.id.length-1),1);
			var apMode = $('apMode' + currentTab);
			if (radio.checked) {
				if (!$('cb_wdsEnabled'+currentTab).checked) {
					apMode.value = 0;
				}
				else if (radio.value <= 2) {
					if (apMode.value >= 3) {
						apMode.value = 2;
					}
					else {
						apMode.value = ($('WCAenabled'+currentTab).checked?2:1);
					}
				}
				else if (radio.value >=3) {
                    if(radio.value == 5) {
                        apMode.value = 5;
                    }
					else if ((apMode.value <= 2) || (apMode.value == 5)) {
                        apMode.value = 3;
					}
					else {
						apMode.value = ($('WCAenabled'+currentTab).checked?3:4);
                    }
                }
			}
		});
		//onclick="form.setApModeWCA(this.checked,1);
		if (eval('typeof(disableChannelonWDS) != \'boolean\'') && !this.activateTab){
            if(($('errorMessageBlock').style.display != 'none') && ($('br_head').innerHTML == 'Bridging cannot be enabled with channel set to Auto!')){
            }
            else{
                setActiveContent();
            }
        }
	},

	getWdsTab: function() {
		var apMode = parseInt($('apMode' + this.currentTab).value);
		if (apMode == 0) {
			var id = 1;
		}
		else if (apMode <= 2) {
			var id = 1;
		}
		else if (apMode == 5) {
			if (!config.P2P.status)
				var id = 2;
			else
				var id = 3;
		}
		else if (apMode >=3) {
			if (!config.P2P.status)
				var id = 1;
			else
				var id = 2;
		}
		return id;
	},

	getProfileCount: function() {
		var apMode = parseInt($('apMode' + this.currentTab).value);
		var profileCount = config.NO_OF_WDS_VAPS.count;
		if (apMode == 0) {
			if (config.P2P.status) {
				profileCount = 1;
			}
			else if (config.CLIENT.status) {
				profileCount = 0;
			}
		}
		else if (apMode <= 2) {
			profileCount = 1;
		}
		else if (apMode == 5) {
			profileCount = 0;
		}
		return profileCount;
	},

	activateSubTab: function() {
        if(($('apMode' + this.currentTab).value == 5) &&(parseInt($S('wdsMode'+this.currentTab)) !=5) ){
            if(($('errorMessageBlock').style.display != 'none') && ($('br_head').innerHTML == 'Please address the fields highlighted!')){
                setRadioCheckById('wdsMode'+this.currentTab,5);
                return;
            }
            $RD('macClone'+this.currentTab).each(function(input) {
				input.disabled=true;
			});
	if($('macCloneAddr'+this.currentTab) != undefined)
            $('macCloneAddr'+this.currentTab).disabled = true;
        }
        if (returnVal) {
			if (!this.activateTab) {
				this.setApModeWCA();
			}
			if ($A(arguments)[1]!=undefined)
				var id = $A(arguments)[1];
			else {
				var id = this.getWdsTab();
			}
			var mainId = 'includeSubTab';

			for (var i = 1; i <= this.wdsTabCount; i++) {
				if (id == i) {
					if($(mainId + this.currentTab + i) != undefined)
						$(mainId + this.currentTab + i).className = "Active";
				}
				else {
					if($(mainId + this.currentTab + i) != undefined)
						$(mainId + this.currentTab + i).className = "";
				}
			}
			$('WCAenabled' + this.currentTab).checked = (($('apMode' + this.currentTab).value == 2) || ($('apMode' + this.currentTab).value == 3));
			if (!this.activateTab){
                if(($('errorMessageBlock').style.display != 'none') && ($('br_head').innerHTML == 'Bridging cannot be enabled with channel set to Auto!')){
                    activateApply(false);
                    disableButtons(["edit"]);
                }
                else{
                    setActiveContent();
                }
            }

			if ($('apMode' + this.currentTab).value != 5 && $('apMode' + this.currentTab).value != 0) {
				if ((eval('typeof(disableWDSonChannel'+this.currentTab+')') == 'boolean')) {
					showMessage('Bridging cannot be enabled with channel set to Auto!');
					activateApply(false);
				}
				else {
                    if(($('errorMessageBlock').style.display != 'none') && ($('br_head').innerHTML == 'Bridging cannot be enabled with channel set to Auto!')){
                        disableButtons(["edit"]);
                    }
                    else{
                        enableButtons(["edit"]);
                    }
					
				}
			}
			if ($('apMode' + this.currentTab).value == 5) {
				disableButtons(["edit"]);
				hideMessage();
				activateApply();
				if (config.CLIENT.status) {
					$RD('macClone'+this.currentTab).each(function(input) {
						input.disabled=false;
					});
					this.toggleMACCloneAddress();
				}
			}
			this.toggleProfiles(this.getProfileCount());
		}
		else {
			return false;
		}
	},

	toggleMACCloneAddress: function() {
		var status = $S('macClone'+this.currentTab);
        var wdsModeSelected = parseInt($S('wdsMode'+this.currentTab));
		if (status=='0') {
			if($('macCloneAddr'+this.currentTab) != undefined){
				$('macCloneAddr'+this.currentTab).disabled=true;
			}
			if (($('activeMode').value != "") && (wdsModeSelected == 5)) {
				hideMessage();
			}
		}
		else {
			if($('macCloneAddr'+this.currentTab) != undefined){
				$('macCloneAddr'+this.currentTab).disabled=false;
			}
		if(config.WN604.status){
	        $('roamingRSSIThreshold'+this.currentTab).disabled=false;
        	$RD('backgrndScanRadio'+this.currentTab).each(function(input) {
                	input.disabled=false;
        	});
        	$RD('scanTypeRadio'+this.currentTab).each(function(input) {
                	input.disabled=false;
        	});
			$RD('clearBSSListRadio'+this.currentTab).each(function(input) {
                	input.disabled=false;
        	});
			$('bssAgingPeriod'+this.currentTab).disabled=false;
			$('noBeaconTimeout'+this.currentTab).disabled=false;
			$('hwTXRetries'+this.currentTab).disabled=false;
			//$('swTXRetries'+this.currentTab).disabled=false;
			$('roamDebugLevel'+this.currentTab).disabled=false;
			$('bkGrdScanInterval'+this.currentTab).disabled=false;
			$('bkGrdScanTime'+this.currentTab).disabled=false;
			$('roamingRateThreshold'+this.currentTab).disabled=false;
	        for(var i = 0; i <=10; i++){
        	    $('bgChanList'+this.currentTab+i).disabled=false;
       		}	

			$('scanChanListString'+this.currentTab).disabled=false;
	        if($('operateMode'+this.currentTab).value == 0){
        	    if($('roamingRateThreshold'+this.currentTab) != undefined){
                	var dataEnc = $('roamingRateThreshold'+this.currentTab);
                	for(i = 0; i < dataEnc.length; i++){
                  		if((dataEnc.options[i].value != 1) && (dataEnc.options[i].value != 2) && (dataEnc.options[i].value != 5.5) && (dataEnc.options[i].value != 11)){
                    			dataEnc.options[i] = null;
                 		}		
                	}
            	    }
        	}	

		}
	}
	},

	toggleProfiles: function(index) {
		if (returnVal) {
			var table = $('profilesList_' + this.currentTab);
			for (var k = 1; k < table.rows.length; k++)
			{
				if ((index + 1) >= k) {
					try {
						table.rows[k].style.display = "table-row";
					}
					catch (e) {
						table.rows[k].style.display = "block";
					}
				}
				else {
					table.rows[k].style.display = "none";
				}
			}
			if ((eval('typeof(disableWDSonChannel'+this.currentTab+')') == 'boolean') && (parseInt($('apMode' + this.currentTab).value) == 5 || parseInt($('apMode' + this.currentTab).value) == 0)) {
				enableInputFields($('table_wlan'+this.tabIdentifier),false,($('cb_wdsEnabled'+this.currentTab).checked)?["cb_wdsEnabled","wdsMode"]:["cb_wdsEnabled"]);
			}
			else {
				enableInputFields($('table_wlan'+this.tabIdentifier),$('cb_wdsEnabled'+this.currentTab).checked,["cb_wdsEnabled","wdsMode"]);
			}
			$RD('wdsMode'+this.currentTab).each(function(input){
				var currentTab = input.id.substr((input.id.length-1),1);
				input.disabled = !($('cb_wdsEnabled'+currentTab).checked);
			});

			if (config.CLIENT.status) {
				$RD('macClone'+this.currentTab).each(function(input) {
					input.disabled=false;
				});
				this.toggleMACCloneAddress();
			}

			$('apMode'+this.currentTab).disabled=false;
			if ($('activeMode' + this.currentTab).value != '') {
							$('cb_wdsEnabled'+this.currentTab).disabled=false;
			}
			$('wdsEnabled'+this.currentTab).disabled=false;
			enablePrimaryFields();

			if (index == '0') {
				if ((config.CLIENT.status) && ($('macClone_' + this.currentTab)!=undefined))
					$('macClone_' + this.currentTab).style.display = "block";
				$('WCArow_' + this.currentTab).style.visibility = "hidden";
				$('profilesList_'+this.currentTab).style.display = "none";
			}
			else {
				$('WCArow_' + this.currentTab).style.visibility = "visible";
				if ((config.CLIENT.status ) && ($('macClone_' + this.currentTab)!=undefined))
					$('macClone_' + this.currentTab).style.display = "none";
				$('profilesList_' + this.currentTab).style.display = "block";
			}
			if ((eval('typeof(disableWDSonChannel'+this.currentTab+')') == 'boolean') && (parseInt($('apMode'+this.currentTab).value) == 5 || parseInt($('apMode'+this.currentTab).value) == 0))
			{
                if($('tbody_'+this.currentTab) != undefined){
                    $('tbody_'+this.currentTab).disabled = "true";
                }
				try {
					$('WCArow_' + this.currentTab).style.display = "table-row";
				}
				catch (e) {
					$('WCArow_' + this.currentTab).style.display = "block";
				}
				$('WCArow_' + this.currentTab).disabled = false;
			}
		}
		else {
			return false;
		}
	},

	toggleDisplay: function(show) {
        $('previousInterfaceNum').value = this.tabIdentifier;
		var mode = '', mode1 = '';
		if (show == undefined || show == '') {
			if ($('menu1').value != 'Monitoring') {
				if (config.TWOGHZ.status) {
					show="wlan1";
				}
				else {
					if (config.FIVEGHZ.status) {
						show="wlan2";
					}
				}
				mode = getDefaultMode(this.tabIdentifier==1?'TWO':'FIVE');
			}
			else {
				show="wlan1";
			}
		}
		var listArr = new Array(1,2);
		if (!config.DUAL_CONCURRENT.status) {
            mode = this.activeMode;
            if(((parseInt(mode) >=3 )&&(this.currentTab == 0)) || ((parseInt(mode) <=2) && (this.currentTab == 1)))
                mode = '';
		}
		else {
			if (this.currentTab == 0) mode = this.activeMode;
			else if (this.currentTab == 1) mode1 = this.activeMode;

            if (mode1 == undefined || String(mode1) == ''){
                if(config.FIVEGHZ.status && (this.currentTab == 1)){
                    mode1 = getDefaultMode('FIVE');
                }
            }
		}
        if (mode == undefined || String(mode) == ''){
                if(config.TWOGHZ.status && (this.currentTab == 0)){
                    mode = getDefaultMode('TWO');
                }
                if(config.FIVEGHZ.status && (this.currentTab == 1)){
                    mode = getDefaultMode('FIVE');
                }
            }

		for (var i=0; i<listArr.length; i++)
		{
			var bandRow = $('wlan'+listArr[i]);
			if (bandRow == undefined)
				continue;
			if (bandRow.id == this.tabBlock.id) {
				try {
					bandRow.style.display="table-row";
				}
				catch(e) {
					bandRow.style.display="block";
				}
			}
			else {
				bandRow.style.display="none";
			}
        }
			if (config.TWOGHZ.status && this.tabBlock.id == 'wlan1') {
				$('inlineTab1').className = "Active";
				$('inlineTab1').blur();
				if (config.FIVEGHZ.status){
					$('inlineTab2').className = "";
				}
			}
			if (config.FIVEGHZ.status && this.tabBlock.id == 'wlan2') {
				$('inlineTab2').className = "Active";
				$('inlineTab2').blur();
				if (config.TWOGHZ.status){
					$('inlineTab1').className = "";
				}
			}
			if ($('cb_chkRadio'+this.currentTab) != undefined) {
				$('cb_chkRadio'+this.currentTab).disabled=false;
				$('chkRadio'+this.currentTab).disabled=false;
				enable11nFields((mode == '2' || mode == '4')?'show':'hide',this.tabIdentifier);
                if(!config.DUAL_CONCURRENT.status){
                    DispChannelList(this.tabIdentifier,mode);
                }
                else{
                    if (this.currentTab == 0) DispChannelList(this.tabIdentifier,mode);
                        else if (this.currentTab == 1) DispChannelList(this.tabIdentifier,mode1);
                }
				$RD('WirelessMode1').each(function(radio) {
					radio.disabled=false;
					if (mode == '' && config.DUAL_CONCURRENT.status) mode = getDefaultMode(this.tabIdentifier==1?'TWO':'FIVE');
					if (radio.value==mode){
						Event.emulateClick(radio);
					}
				});
				if (config.DUAL_CONCURRENT.status) {
					$RD('WirelessMode2').each(function(radio) {
						radio.disabled=false;
						if (radio.value == mode1){
							Event.emulateClick(radio);
						}
					});
				}
			}
			if (!(config.DUAL_CONCURRENT.status))  {
				if ($('cb_wdsEnabled'+ this.currentTab)!= undefined && !$('cb_wdsEnabled'+ this.currentTab).checked) {
					this.toggleProfiles((config.P2P.status)?(($('apMode'+this.currentTab).value<=2)?1:4):(config.NO_OF_WDS_VAPS.count));
				}
			}
		enablePrimaryFields();
	},

    getModeString: function() {
	this.modeString=this.modesList.split(",");
        if (!config.TWOGHZ.status) {
		this.modeString=this.modesList.split(",");
        }
        if (!config.FIVEGHZ.status) {
           	this.modeString.splice(this.numModes0, this.numModes1, "");
        } 
    },

    checkEmptyActiveMode: function()
    {
        var retVal = false;
        if(config.DUAL_CONCURRENT.status){
            if (String(this.activeMode)== ''){
                retVal = true;
            }
        }else {
                if(($('activeMode0') != undefined) && (String($('activeMode0').value) == '')){
                    retVal = true;
                }

                if (($('activeMode1') != undefined) && (String($('activeMode1').value) != '') && retVal){
                        retVal = false;
                }
            }
            return retVal;
    },

    setActiveMode: function(event,flag)
    { 
        var currentRadio = parseInt($S(this.radioId));
        var alertFlag = false;
        if (currentRadio == -1) {
            $('cb_chkRadio'+this.currentTab).checked = false;
        }
        else {
            this.getModeString();
            var flag1 = true;
            if (String(this.activeMode) != String(currentRadio) && flag && (this.checkEmptyActiveMode() == false)) {
                if(!config.DUAL_CONCURRENT.status){
                    if(this.modeString[this.activeMode] == undefined){
                        var flag1 = true;
                    }
                    else {
                        var flag1 = confirm("Do you want to disable 11"+this.modeString[this.activeMode]+" mode and enable 11"+this.modeString[currentRadio]+" mode?\n\nNote: Click APPLY for the changes to take effect.");
                    }
                }
                else {
                    var flag1 = confirm("Do you want to disable 11"+this.modeString[this.activeMode]+" mode and enable 11"+this.modeString[currentRadio]+" mode?\n\nNote: Click APPLY for the changes to take effect.");
                }
                if (flag1)
                    alertFlag = true;
            }
            else if (String(this.activeMode) == '' && $('cb_chkRadio'+this.currentTab).checked != false) {
                alertFlag=true;
            }
            if (flag1) {
                if (flag) {
                    if ($('cb_chkRadio'+this.currentTab).checked) {
                        if (String(this.activeMode) != String(currentRadio) && currentRadio+1 != false) {
                            this.activeModeField.value=currentRadio;
                            $('activeMode'+this.currentTab).value = currentRadio;
                            this.activeMode = currentRadio;

                            if(!config.DUAL_CONCURRENT.status){
                               var chkRad = $('chkRadio'+String((this.id == 1)?(this.id):(this.id-2)));
                               var actMode = $('activeMode'+String((this.id == 1)?(this.id):(this.id-2)));
                                if(chkRad != undefined){
                                    chkRad.value = 0;
                                }
                                if(actMode != undefined){
                                    actMode.disabled = true;
                                    actMode.value = '';
                                    this.activeModeField.disabled =false;
                                }
                            }
                        }
                    }
                    else {
                        //this.activeModeField.value = '';
                        this.activeMode = '';
                    }
                }
                this.setLabelText(currentRadio);
            }
        }
        if (alertFlag) {
            showMessage('Click APPLY for the changes to take effect!');
        }
        this.enableFields(currentRadio);
        if(config.SCH_WIRELESS_ON_OFF.status){
            if($('cb_chkRadio'+this.currentTab).checked){
                if($('radioBkup'+this.currentTab).value != '1'){
                    $('radioBkup'+this.currentTab).value = '1';
                }
            }
            else{
                if($('radioBkup'+this.currentTab).value != '0'){
                    $('radioBkup'+this.currentTab).value = '0';
                }
            }
        }

    },

    setLabelText: function(currentRadio) {
        var str="802.11";
        var str2="802.11";
        var countMax,count=0, count2=0;
        var group = fetchObjectByAttributeValue('id','WirelessMode1','INPUT');
        countMax = (config.TWOGHZ.status)?(config.MODE11N.status?(this.numModes0-1):1):0;
        if (config.DUAL_CONCURRENT.status) {
            if (this.currentTab == '1') {
                var group = fetchObjectByAttributeValue('id','WirelessMode2','INPUT');
            }
        } 
        for (i=0; i<group.length; i++) {
            var groupMode = 'mode_'+this.modeString[group[i].value];
            if ($(groupMode)) {
                if (parseInt(this.activeMode) == parseInt(group[i].value)) {
                    $(groupMode).className = "Active";
                    Event.observe($(groupMode),"mouseover",function() { showLayer(this); });
                    Event.observe($(groupMode),"mouseout",function() { hideLayer(this); });
                    $(groupMode).innerHTML="11"+this.modeString[group[i].value]+"<img src='../images/activeRadio.gif'><span>Radio is set to 'ON'</span>";
		     if (config.TWOGHZ.status  && group[i].value < this.numModes0) {
                        str=str+"<span class='Active' onmouseover='showLayer(this);' onmouseout='hideLayer(this);'><b class='RadioTextActive'>"+this.modeString[group[i].value]+"<img src='../images/activeRadio.gif'><span>Radio is set to 'ON'</span></b></span>";
                        if(count++ < countMax)
        	              str=str+'/';
                    }
//if (config.FIVEGHZ.status && group[i].value >= this.numModes0 ){
                   if (config.FIVEGHZ.status){
                        str2=str2+"<span class='Active' onmouseover='showLayer(this);' onmouseout='hideLayer(this);'><b class='RadioTextActive'>"+this.modeString[group[i].value]+"<img src='../images/activeRadio.gif'><span>Radio is set to 'ON'</span></b></span>";
                        if (count2++ < this.numModes1-1)
                            str2=str2+'/';
                    }
                }
                else {
                    $(groupMode).className = "";
                    $(groupMode).innerHTML="11"+this.modeString[group[i].value];
                    if (config.TWOGHZ.status && group[i].value < this.numModes0 ){
                        str=str+"<span class='Active'><b class='RadioText'>"+this.modeString[group[i].value]+"</b></span>";
                        if(count++ < countMax)
                          str=str+'/';
                    }
//if (config.FIVEGHZ.status && group[i].value >= this.numModes0 ) {
                 if (config.FIVEGHZ.status) {
                        str2=str2+"<span class='Active'><b class='RadioText'>"+this.modeString[group[i].value]+"</b></span>";
                        if (count2++ < this.numModes1-1)
                            str2=str2+'/';
                    }
                }
            }
        }
        if (config.DUAL_CONCURRENT.status) {
            if (config.TWOGHZ.status && this.currentTab == '0') {
                $('inlineTabLink1').update(str);
            }
            if (config.FIVEGHZ.status && this.currentTab == '1') {
                $('inlineTabLink2').update(str2);
            }
            //hideMessage('Wireless Radio is turned off!');
        }
        else {
            if(this.activeMode == ''){
                if(config.TWOGHZ.status && this.currentTab == 0)
                    $('inlineTabLink1').update(str);
                if (config.FIVEGHZ.status && this.currentTab == 1)
                    $('inlineTabLink2').update(str2);

            }
            if ((config.TWOGHZ.status) && ((parseInt(this.activeMode) >= 0) && (parseInt(this.activeMode) <= 2) )) {
                $('inlineTabLink1').update(str);
                if (config.FIVEGHZ.status)
                    $('inlineTabLink2').update(str2);
            }
            if ((config.FIVEGHZ.status) && ((parseInt(this.activeMode) >= 3) && (parseInt(this.activeMode) <= 4) )) {
                $('inlineTabLink2').update(str2);
                if (config.TWOGHZ.status)
                    $('inlineTabLink1').update(str);
            }
        }
        if (this.activeMode != '') {
            hideMessage('Wireless Radio is turned off!');
        }
    },

    enableFields: function(mode)
    {
        var show = 'table_wlan'+this.tabIdentifier;
        if (String(mode) != '' && $('cb_chkRadio'+this.currentTab)!= undefined) {
            if (String(this.activeMode) == String(mode)) {
                $('cb_chkRadio'+this.currentTab).checked = true;
            }
            else {
                $('cb_chkRadio'+this.currentTab).checked = false;
            }
        }
        if ($('cb_chkRadio'+this.currentTab)!= undefined) {
            flag = $('cb_chkRadio'+this.currentTab).checked;
        }
        else {
            flag = ((String(this.activeMode) == String(mode)) && (String(mode) != ''));
            if((flag == false) && (this.currentTab == '1'))
                flag = true;
        }
        if ($(show)) {
            var rowsObj = $(show).rows;
            if (rowsObj != undefined) {
                for (var i = 0; i < rowsObj.length; i++) {
                    if(($('cb_chkRadio'+this.currentTab) != undefined)){
                        if(mode == 2 || mode == 4){
                            if(($('apMode'+this.currentTab) != undefined) && ($('apMode'+this.currentTab).value == '5'))
                                var excludeArray = ['chkRadio','WirelessMode','activeMode','idbroadcastSSID','ChannelList','DatarateList','MCSrateList','Bandwidth','GI','PowerList'];
                            else
                                var excludeArray = ['chkRadio','WirelessMode','activeMode'];
                        }
                        else{
                            if(($('apMode'+this.currentTab) != undefined) && ($('apMode'+this.currentTab).value == '5'))
                                var excludeArray = ['chkRadio','WirelessMode','activeMode','idbroadcastSSID','ChannelList','DatarateList','MCSrateList','Bandwidth','GI','PowerList'];
                            else
                                var excludeArray = ['chkRadio','WirelessMode','activeMode','MCSrateList','Bandwidth','GI'];
                        }
                        enableInputFields(rowsObj[i],flag,excludeArray);
                    }
                    else if(($('wdsEnabled'+this.currentTab) != undefined)){
                        enableInputFields(rowsObj[i],$('cb_wdsEnabled'+this.currentTab).checked,[]);
                    }
                }
            }
            else {
                enableInputFields($(show),flag,['activeMode']);
            }
        }
        enablePrimaryFields();
    },

    enableMode: function(event,mode)
    {
        DispChannelList(this.tabIdentifier,String(mode));
        enable11nFields((mode=='2'||mode=='4')?'show':'hide',this.tabIdentifier);
        this.enableFields(mode);
    }

}

 function setHelpUrl(currentTab , id){
      if (($('cb_chkRadio'+ currentTab) != undefined) || ($('iRTSThreshold'+ currentTab) != undefined)){
            if (id == 2){
                if(($('helpURL').value.indexOf('_g')) != -1){
                    $('helpURL').value=$('helpURL').value.replace('_g','_a');
                }
                else{
                    if(($('helpURL').value.indexOf('_a')) == -1){
                        $('helpURL').value=$('helpURL').value + '_a';
                    }
                }
            }
            else{
                if(($('helpURL').value.indexOf('_a')) != -1){
                    $('helpURL').value=$('helpURL').value.replace('_a','_g');
                }
                else {
                    if(($('helpURL').value.indexOf('_g')) == -1){
                        $('helpURL').value=$('helpURL').value + '_g';
                    }
                }
            }
        }

    }

function showMessage(msg)
{
	if (msg != '' || msg != undefined)
		$('br_head').innerHTML = msg;
	$('errorMessageBlock').show();
}

function hideMessage(msg) {
    if (msg == undefined) {
        $('errorMessageBlock').hide();
    }
	else if (msg == $('br_head').innerHTML) {
		$('errorMessageBlock').hide();
	}
}

function enableButtons(btnList, flag)
{
	if (flag == undefined) var flag = true;
	btnList.each(function(buttonId) {
		if (window.top.frames['action'].$(buttonId) != undefined) {
			window.top.frames['action'].$(buttonId).disabled = !flag;
			if (flag)
				window.top.frames['action'].$(buttonId).src = window.top.frames['action'].$(buttonId).src.replace('_off.gif','_on.gif');
			else
				window.top.frames['action'].$(buttonId).src = window.top.frames['action'].$(buttonId).src.replace('_on.gif','_off.gif');
		}
	});
}

function disableButtons(btnList)
{
	enableButtons(btnList,false);
}

function enablePrimaryFields()
{
	$('menu1').disabled=false;
	$('menu2').disabled=false;
	$('menu3').disabled=false;
	$('menu4').disabled=false;
	$('mode7').disabled=false;
	$('mode8').disabled=false;
	$('mode9').disabled=false;
}

function toggleMenu(menuId)
{
	var loopList = document.getElementsByTagName('UL');
	var flag = true;
	for (var i = 0; i < loopList.length; i++) {
		if (('div_' + menuId) == loopList[i].id) {
			if ($(loopList[i]).style.display != 'none') {
				$(loopList[i]).style.display='none';
				$('img_' + menuId).src = 'images/arrow_right.gif';
				flag = false;
			}
			else {
				$(loopList[i]).style.display = 'inline';
				$('img_' + menuId).src = 'images/arrow_down.gif';
			}
				if(config.AWSDAP350.status)
					$('third_' + menuId).style.color = '#B3C188';
				else
					$('third_' + menuId).style.color = '#FFA400';

		}
		else {
			$(loopList[i]).style.display='none';
			$('img_' + menuId).src = 'images/arrow_right.gif';
			$(loopList[i].id.replace('div_','third_')).style.color = '#46008F';
		}
	}
	if (flag) {
		if(config.AWSDAP350.status)
			$('div_' + menuId + '_1').childNodes[0].style.color = '#B3C188';
		else
			$('div_' + menuId + '_1').childNodes[0].style.color = '#FFA400';
		Event.emulateClick($('div_' + menuId + '_1'));
	}
}

function toggleNTPServer(status)
{
	if (status=='1') {
		$('cb_customntp').disabled=false;
		$('ntpservername').disabled=!$('cb_customntp').checked;
	}
	else {
		$('cb_customntp').disabled=true;
		$('ntpservername').disabled=true;
	}
}
function checkCustomNTPServer(checkState)
{
	if($('ntpservername') != undefined)
        $('ntpservername').disabled=!checkState;
	if(checkState==false)
	{
        	if($('hiddenntpservername') != undefined){
            	$('hiddenntpservername').disabled=false;
	    	if(config.AWSDAP350.status)
            		$('hiddenntpservername').value="time.windows.com";
	    	else
            		$('hiddenntpservername').value="time-b.netgear.com";
        	}
		if($('ntpservername') != undefined){
			if(config.AWSDAP350.status)
            			$('hiddenntpservername').value="time.windows.com";
			else
            			$('ntpservername').value="time-b.netgear.com";
		}
	}
	else
	{
        	if($('hiddenntpservername') != undefined)
            		$('hiddenntpservername').disabled=true;
	}
}
function toggleBandSteering(status)
{
	if (status=='1') {
		$('RssiThreshold24GHz').disabled=false;
		$('RssiThreshold5GHz').disabled=false;
	}
	else {
		$('RssiThreshold24GHz').disabled=true;
		$('RssiThreshold5GHz').disabled=true;
	}
}
function checkSpecialChars()
{
	/** allow special characters

	if ((event.keyCode > 32 && event.keyCode < 48) || (event.keyCode > 57 && event.keyCode < 65) ||
	(event.keyCode > 90 && event.keyCode < 97)) event.returnValue = false;

	**/
}


function checkInputKeys(secretKey)
{
	var tempStr = secretKey;
	var count = 0;

	if(tempStr == "")
		return 1;
	for(i=0;i<tempStr.length;i++)
	{
		var subStr = tempStr.charAt(i);
		if (subStr == "*")
		{
			count++;
		}
	}
	if(count == tempStr.length)
		return 0;
	else
		return 1;
}



//dhcpserversettings page functions

function disableDhcps()
{
	graysomething();
}

// Called when DHCP server is being enabled
function enableDhcps()
{
	graysomething();
}
var posit;

function validateIPField(event,obj)
{
	var KeyValue = event.keyCode;
	if (KeyValue == 0x08) {       // BackSpace
		if (posit > 0) {
			posit=posit-1;
		}
		return;
	}
	if (KeyValue == 0x09 ||     // Tab
		KeyValue == 0x2e        // Delete
	) {
		return;
	}
	if ((KeyValue < 0x30 || KeyValue > 0x39) &&
		(KeyValue < 0x60 || KeyValue > 0x69) // numbers
	) {
		event.returnValue = false;
		return;
	}
	//if (posit++ == 3) {
	//	obj.form.elements[getIndex(obj)+1].focus();
	//}
}

function isInteger(s){
		for (var i = 0; i < s.length; i++){
				// Check that current character is number.
				var c = s.charAt(i);
				if (((c < "0") || (c > "9")))
			return false;
		}
		// All characters are numbers.
		return true;
}

function toggleCheckBoxes(target,obj)
{
	list=fetchObjectByAttributeValue('id',target,'INPUT');
		for (var i = 0; i < list.length; i++){
		list[i].checked=obj.checked;
		}
	return true;
}


function graysomething(self,flag)
{
	hideMessage();
	var inputs=fetchAllInputFields();
	for (var a = 0; a < inputs.length; a++)
	{
		if (inputs[a].id != self.id) {
			if (!flag)
				inputs[a].disabled = !(!(parseInt(self.value)));
			else
				inputs[a].disabled = !(!(!(parseInt(self.value))));

		}
	}
	/*if(self.id=="tr069Status")
   		checkSNMPStatus(self);
	if(self.id=="snmpStatus")
	  checkTR069Status(self);*/
}

/*
@ date: 22 Nov 2011
@ author: Moulidaren, Arada Systems
@ param: self Radio object
@ description: This method will disable all the other fields in the form when cloud is disabled and will enable when the cloud is enabled.
This method will also act in enabling the user to edit IP Settings when the dhcp client mode is disabled and vice versa. 
*/
function grayOutForCloud(self){
	//Hide the form warning alerts.
	hideMessage();
	//To obtain all the input fields from the GUI form.
	var inputs=fetchAllInputFields();
	for(var a=0; a<inputs.length;a++){
		//For all other input fields other than the selected input field.
		// obj.disabled = 1 will gray out the content.
		// obj.disabled = 0 will enable the content.
		if(inputs[a].id != self.id) {
			if(self.id == "enableCloud") {
				if(self.value == 1) {
					if((inputs[a].id == "enabledhcp" && inputs[a].value == "1") || (inputs[a].id == "enabledhcp" && inputs[a].value == "0"))
						inputs[a].disabled = 0;
					else
						inputs[a].disabled = 1;						
				}
				else {
					inputs[a].disabled = 1;
				}		
			}
			if((self.id == "enabledhcp") && (inputs[a].id != "enableCloud") && (inputs[a].id != "enableUi") && (inputs[a].id != "rebootAP") && (inputs[a].id != "fwVer") && (inputs[a].id != "upTimeDays") && (inputs[a].id != "upTimeHours") && (inputs[a].id != "upTimeMinutes")) {
				if(self.value == 1) {
					inputs[a].disabled = 1;
				}
				else {
					inputs[a].disabled = 0;
				}
			}
		}
	}
}
function IPgraysomething(self,flag,id)
{
	hideMessage();
	var inputs=fetchAllInputFieldsByTable(id);
	for (var a = 0; a < inputs.length; a++)
	{
		if (inputs[a].id != self.id) {
			if (!flag)
				inputs[a].disabled = !(!(parseInt(self.value)));
			else
				inputs[a].disabled = !(!(!(parseInt(self.value))));
		}
	}
}

function prepareURL(fourth, third, second, primary, id, iface, extra)
{
	if (top.master._disableAll != undefined && top.master._disableAll == true) {
		return ;
	}
	if (id && $S(id+'_'+iface) == -1 ) {
		alert("Please select a Profile!");
		return false;
	}
	if ((parent.parent.frames['master'].progressBar!= undefined) && (!parent.parent.frames['master'].progressBar.isOpen)) {
		parent.parent.frames['master'].progressBar.open();
	}

	setActiveContent(false);

	if ($('menu4')!=undefined)
		$('menu4').value=fourth;
	if ($('menu3')!=undefined)
		$('menu3').value=third;
	if ($('menu2')!=undefined)
		$('menu2').value=second;
	if ($('menu1')!=undefined)
		$('menu1').value=primary;
	if (id && $('mode7')!=undefined) {
		$('mode7').value=$S(id+'_'+iface);
		if ($('mode7').value == -1) {
			$('mode7').value=$S(id);
			if ($('mode7').value == -1) {
			}
		}
	}
	if (iface && $('mode8')!=undefined)
		$('mode8').value=iface;
	if (extra && $('mode9')!=undefined)
		$('mode9').value=extra;

	document.dataForm.target="master";
        
            if($('wdsMode'+iface) != undefined){
                document.dataForm.action="index.php?page=master&wdsLinkMode="+$S('wdsMode'+iface);
            }else{
                document.dataForm.action="index.php?page=master";
            }
	document.dataForm.submit();
}
function redirectHome(sessionCheck,username,password)
{
	var flag = false;
        if (sessionCheck) {
             flag = confirm('Another Session is currently active!\nDo you want to terminate the remote session?');
             if (flag) {
			var oOptions = {
							method: "get",
				asynchronous: false,
				parameters: { username: username, password:password, id: Math.floor(Math.random()*1000001) },
							onSuccess: function (oXHR, oJson) {
					var response = oXHR.responseText;
                                         if(response != 'recreateok') {
						 alert('Error deleting old session!');
						doLogout();
					}
					else {
						/*var pword;
						var oOptions = {
						    method: "get",
						    asynchronous: false,
								parameters: { action: 'alertDefaultpwd'},
						    onSuccess: function (oXHR, oJson) {
							var response = oXHR.responseText;
									//alert("response123 = "+response);
									pword=response;
									},
						    onFailure: function (oXHR, oJson) {
							alert("There was an error with the process, Please try again!");
						    }
						};
						var req = new Ajax.Request('forcePasswordChange.php?id='+Math.random(10000,99999), oOptions);
						if(pword=="password"){
						var flag=alert("The current password is default password. Please go to the Business Central and change the default password for this location to a secured password.");	
						}*/
						window.top.location.href = "index.php";
					}
							},
							onFailure: function (oXHR, oJson) {
					alert("There was an error with the connection, Please try again!");
							}
					};
			var req = new Ajax.Request('recreate.php', oOptions);
		}
		else {
			doLogout(false);
		}
	}
	else {
		/*var pword;
		var oOptions = {
		    method: "get",
		    asynchronous: false,
				parameters: { action: 'alertDefaultpwd'},
		    onSuccess: function (oXHR, oJson) {
		        var response = oXHR.responseText;
					//alert("response123 = "+response);
					pword=response;
					},
		    onFailure: function (oXHR, oJson) {
		        alert("There was an error with the process, Please try again!");
		    }
		};
		var req = new Ajax.Request('forcePasswordChange.php?id='+Math.random(10000,99999), oOptions);
		if(pword=="password"){
		var flag=alert("The current password is default password. Please go to the Business Central and change the default password for this location to a secured password.");	
		}*/
		window.top.location.href = "index.php";
	}
}


function doLogin(event)
{
	var ev = getEvent(event);
	var username=$('username').value;
        var password=$('password').value;
	if (parent.parent.frames['master'].progressBar != undefined)
	parent.parent.frames['master'].progressBar.open();
	Form.disable(document.dataForm);
	var oOptions = {
			method: "get",
			asynchronous: false,
			parameters: { username: $('username').value, password: $('password').value, id: Math.floor(Math.random()*1000001) },
						onSuccess: function (oXHR, oJson) {
				var response = oXHR.responseText;
				if(response == 'loginok') {
					//alert("Login OK!");
					redirectHome(false,username,password);
				}
				else if (response == 'sessionexists') {
					redirectHome(true,username,password);
				}
				else if(response == 'restricted')
				{
					parent.parent.frames['master'].progressBar.close();
					showMessage('Admin is already logged in, restricted to login');
					$('username').value = '';
					$('password').value = '';
					Form.enable(document.dataForm);
                    $('username').focus();
                    ev.returnValue = false;
					return false;					
				}
				else {
					if (parent.parent.frames['master'].progressBar != undefined)
							parent.parent.frames['master'].progressBar.close();
						showMessage('Invalid Username / Password!');
						$('username').value = '';
						$('password').value = '';
						Form.enable(document.dataForm);
                        $('username').focus();
                        ev.returnValue = false;
						return false;
				}
				return false;
						},
						onFailure: function (oXHR, oJson) {
						alert("There was an error with the connection, Please try again!");
						}
				};
		var req = new Ajax.Request('login.php', oOptions);
}

function doLogout(flag)
{
	if (parent.parent.frames['master'].progressBar != undefined)
		parent.parent.frames['master'].progressBar.open();
	var oOptions = {
		method: "get",
		asynchronous: false,
		parameters: { logout: 'logout', emptySession: flag, id: Math.floor(Math.random()*1000001) },
		onSuccess: function (oXHR, oJson) {
			var response = oXHR.responseText;
			if(response == 'logoutok') {
				//alert("Logout OK!");
				window.top.location.href = "index.php";
			}
			else {
				setTimeout(function() { new Ajax.Request('logout.php', oOptions); },1000);
						return false;
			}
			return false;
		},
		onFailure: function (oXHR, oJson) {
			alert("There was an error with the connection, Please try again!");
			parent.parent.frames['master'].progressBar.close();
		}
	};
	var req = new Ajax.Request('logout.php', oOptions);
}

function doPacketCapture(actionToDo)
{
var oOptions = {
			method: "get",
			asynchronous: false,
			parameters: { checkActiveSession: 'check', id: Math.floor(Math.random()*1000001) },
						onSuccess: function (oXHR, oJson) {
				var response = oXHR.responseText;
				if(response == 'expired') {
					window.top.location.href = "index.php";
				}
				else {
							if ((actionToDo == 'start') && (document.getElementById('saveas').disabled == false)){
							var flag = confirm('Previous capture will be deleted!\nDo you want to continue?');
							if(flag)
									document.getElementById('saveas').disabled=true;
								else
									return;
							}
							var osubOptions = {
								method: "get",
								asynchronous: false,
								parameters: { action: actionToDo},
								onSuccess: function (oXHR, oJson) {
									var response = oXHR.responseText;
									if (response == 'success'){
										switch(actionToDo){
											case "start":
												enablePktCaptureStop();
												break;
											case "stop":
												enablePktCaptureStart();
												break;
										}
									}
								},
								onFailure: function (oXHR, oJson) {
									alert("There was an error with the process, Please try again!");
								}
							};
							var req = new Ajax.Request('packetCapture.php?id='+Math.random(10000,99999), osubOptions);
							return req.responseText;
				}
				return false;
						},
						onFailure: function (oXHR, oJson) {
						alert("There was an error with the connection, Please try again!");
						}
				};
		var req = new Ajax.Request('checkSession.php', oOptions);		
}

function enablePktCaptureStop()
{

    if (document.getElementById('stop').disabled == true){
        document.getElementById('stop').disabled=false;
    }
    if (document.getElementById('start').disabled == false){
        document.getElementById('start').disabled=true;
    }

}

function enablePktCaptureStart()
{
    if (document.getElementById('stop').disabled == false){
        document.getElementById('stop').disabled=true;
    }
    if (document.getElementById('start').disabled == true){
        document.getElementById('start').disabled=false;
    }
    if (document.getElementById('saveas').disabled == true){
        document.getElementById('saveas').disabled=false;
    }
}

function processLogout()
{
	doLogout();
}



function passwd_disable()
{
	document.dataForm.currentpassword.value = "";
	document.dataForm.adminpassword.value = "";
	document.dataForm.adminpasswordconfirm.value = "";

	document.dataForm.currentpassword.disabled = true;
	document.dataForm.adminpassword.disabled = true;
	document.dataForm.adminpasswordconfirm.disabled = true;
}


function passwd_enable()
{
	document.dataForm.currentpassword.disabled = false;
	document.dataForm.adminpassword.disabled = false;
	document.dataForm.adminpasswordconfirm.disabled = false;
}

function toggleSyslog(obj)
{
	var fields=fetchAllInputFields();
	for(i=0;i<fields.length;i++)
	{
		if (fields[i].id != obj.id)
			fields[i].disabled=!obj.checked;
	}
}

function changeLabel(block,str)
{
	obj=$('modeName'+block);
	//alert(obj.tagName);
	obj.innerHTML=str;
}

function showNewMacAddress()
{
	ableControls(false);
	$('AddMacLayer').style.display='block';
}

function hideNewMacAddress()
{
	$('AddMacLayer').style.display='none';
	ableControls(true);
}

function showBand(obj)
{
	ableInputFields(!obj.activeBand);
//	alert($('band2').hasClassName('legend'));
//	alert($('band5').hasClassName('legendActive'));
	//$('band5').toggleClassName('legendActive');
	//$('band5').toggleClassName('legend');
	//$('band2').toggleClassName('legend');
	//$('band2').toggleClassName('legendActive');
}

function ableInputFields(flag)
{
	if (flag) {
		Form.enable(document.dataForm);
	}
	else {
		Form.disable(document.dataForm);
	}
}

function ableControls(flag)
{
	inputs=fetchObjectByAttributeValue('type',"checkbox",'INPUT');
	for (var k=0;k < inputs.length; k++)
	{
		inputs[k].disabled=!flag;
		if (inputs[k].dependent) {
			if (flag)
				$(inputs[k].dependent).disabled=!inputs[k].checked;
			else
				$(inputs[k].dependent).disabled=true;
		}
	}
	$('refreshAWS').disabled = !flag;
	$('add').disabled = !flag;
	$('addmac').disabled = !flag;
	$('delete').disabled = !flag;
}

function DispProfiles(str)
{
	var obj1=fetchObjectByAttributeValue("mode","secA","TR");
	var obj2=fetchObjectByAttributeValue("mode","secNA","TR");

	if (str=="secA")
	{
		show(obj1);
		hide(obj2);
	}
	else
	{
		hide(obj1);
		show(obj2);
	}
}

function DisplayExtControls(val){
    if(config.EXT_CHAN.status){
        var obj=fetchObjectsByAttribute("mode","TR");
        var mode = "extCtrl";
            for (var k=0; k < obj.length; k++) {
                    if (obj[k].getAttribute("mode")==mode && ((val == 1) || (val == 2))) {
                            showNenableExtControls(obj[k]);
			    
 			    var extProtSpecCtrl = $('extProtSpec_0');
                            extProtSpecCtrl.options[0].selected="selected";
                            var extChanOffsetCtrl = $('extChanOffset_0');
                            extChanOffsetCtrl.options[0].selected="selected";
                    }
                    else if (obj[k].getAttribute("mode")==mode && val == 0) {
                            hideNdisableExtControls(obj[k]);
                    }
            }
    }
}
function hideNdisableExtControls(obj)
{
	obj.style.display="none";
	obj.disabled = true;
	$(obj).descendants().each(function (element) {
		element.disabled = true;
	});
	obj.getElementsByTagName('td')[1].getElementsByTagName('select')[0].disabled=true;
}

function showNenableExtControls(obj)
{
	try {
		obj.style.display="table-row";
	}
	catch(e) {
		obj.style.display="block";
	}
	obj.disabled = false;
	$(obj).descendants().each(function (element) {
		element.disabled = false;
	});
	obj.getElementsByTagName('td')[1].getElementsByTagName('select')[0].disabled=false;
}
function DisplaySettings(mode,val,option)
{
	if (val == undefined) {
		val = $('key_size_11g').value;
	}
	var obj=fetchObjectsByAttribute("mode","TR");
	var flag=false;

	if (mode=='4' || mode == '16') {
		var List=new Array('TKIP','TKIP + AES');
		var ListVal=new Array('2','6');
		$('encryption').value='2';
		$('wepKeyType').disabled=true;
		if (mode=='4' && option!=1) {
			flag=true;
		}
	}
	else if (mode=='8' || mode == '32') {
		var List=new Array('AES','TKIP + AES');
		var ListVal=new Array('4','6');
		$('encryption').value='4';
		$('wepKeyType').disabled=true;
		if (mode=='8' && option!=1) {
			flag=true;
		}
	}
	else if (mode=='12' || mode == '48') {
		var List=new Array('TKIP + AES');
		var ListVal=new Array('6');
		$('encryption').value='6';
		$('wepKeyType').disabled=true;
		if (mode=='12' && option!=1) {
			flag=true;
		}
	}
	else if (mode=='1' && option!=1) {
		var List=new Array('64 bit WEP','128 bit WEP', '152 bit WEP');
		var ListVal=new Array('64','128','152');
		$('wepKeyType').disabled=false;
		$('wepKeyType').value='64';
		$('encryption').value='1';
	}
	else if (mode!= '0' && option != 1) {
		var List=new Array('None');
		var ListVal=new Array('0');
		$('encryption').value='0';
		$('wepKeyType').disabled=true;
		flag = true;
	}
	else {
		var List=new Array('None','64 bit WEP','128 bit WEP', '152 bit WEP');
		var ListVal=new Array('0','64','128','152');
		$('encryption').value='0';
		$('wepKeyType').disabled=true;
	}


	var dataEnc = $('key_size_11g');
	var selEnc = dataEnc.value;
	do
		dataEnc.options[0] = null;
	while (dataEnc.length > 0);

	for (var j=0; j<List.length; j++)
	{
		var opt=document.createElement('OPTION');
		opt.text=List[j];
		opt.value=ListVal[j];
		if (selEnc == ListVal[j] && opt!=1 && mode!='0' && mode != '16' && mode != '4' && mode != '32' && mode != '8') {
			if (opt.value > 8) {
				$('wepKeyType').value=opt.value;
			}
			else {
				$('encryption').value=opt.value;
			}
			opt.selected="selected";
		}
//alert(opt.text+'======='+opt.value);
		try {
			dataEnc.add(opt, null); // standards compliant; doesn't work in IE
		}
		catch(ex) {
			dataEnc.add(opt); // IE only
		}
	}

	for (var k=0; k < obj.length; k++) {
		if (obj[k].getAttribute("mode")==mode && dataEnc.value != 0) {
			showNenable(obj[k]);
		}
		else if ((mode=='16' || mode == '32' || mode == '48') && obj[k].getAttribute("mode")=='16') {
			showNenable(obj[k]);
		}
		else {
			hideNdisable(obj[k]);
		}
	}
	if(config.WN604.status && (mode == '16' || mode == '32' || mode == '48')){
            toggleWPAMethods();
        }

	if(config.IPV6.status)
	{
		if (flag && ($('radiusEnabled').value == 'false' && $('radiusv6Enabled').value == 'false' )) {
			alert("To use this Network Authentication method, "+
				"you must first configure a Primary Authentication Server on the Radius Server Settings screen (Configuration -> Security -> Advanced -> Radius Server).");
		}
	}
	else if (flag && $('radiusEnabled').value == 'false' ) {
		alert("To use this Network Authentication method, "+
			"you must first configure a Primary Authentication Server on the Radius Server Settings screen (Configuration -> Security -> Advanced -> Radius Server).");
	}
}


function showRadiusAlert(obj)
{
	if (obj.value == '2') {
		if ($('radiusEnabled')!= undefined && $('radiusEnabled').value=='false') {
			alert("To use this Network Authentication method, "+
				"you must first configure a Primary Authentication Server on the Radius Server Settings screen (Configuration -> Security -> Advanced -> Radius Server).");
			obj.value = '1';
			if((obj.id == 'accesscontroldb0') && ($('accessControlMode0') != undefined))
                            $('accessControlMode0').value = '1';
                        else if((obj.id == 'accesscontroldb1') && ($('accessControlMode1') != undefined))
                            $('accessControlMode1').value = '1';
			obj.options[0].selected = true;
			event.returnValue = false;
			return false;
		}
	}
	return true;
}

function wdsDisplaySettings(mode,val,option)
{
	if (val == undefined) {
		val = $('key_size_11g').value;
	}
	var obj=fetchObjectsByAttribute("mode","TR");
	var flag=false;

	if (mode == '2') {
		var List=new Array('TKIP');
		var ListVal=new Array('2');
		$('wepKeyType').disabled=true;
		$('encryption').value='2';
	}
	else if (mode=='4') {
		var List=new Array('AES');
		var ListVal=new Array('4');
		$('wepKeyType').disabled=true;
		$('encryption').value='4';
	}
	else if (mode=='1' && option!=1) {
		var List=new Array('64 bit WEP','128 bit WEP', '152 bit WEP');
		var ListVal=new Array('64','128','152');
		$('wepKeyType').disabled=false;
		$('wepKeyType').value='64';
		$('encryption').value='1';
	}
	else if (mode!= '0' && option != 1) {
		var List=new Array('None');
		var ListVal=new Array('0');
		$('encryption').value='0';
		flag = true;
	}
	else {
		var List=new Array('None','64 bit WEP','128 bit WEP', '152 bit WEP');
		var ListVal=new Array('0','64','128','152');
		$('encryption').value='0';
	}


	var dataEnc = $('key_size_11g');
	var selEnc = dataEnc.value;
	do
		dataEnc.options[0] = null;
	while (dataEnc.length > 0);

	for (var j=0; j<List.length; j++)
	{
		var opt=document.createElement('OPTION');
		opt.text=List[j];
		opt.value=ListVal[j];
		if (selEnc == ListVal[j] && opt!=1 && mode!='0') {
			if (opt.value > 8) {
				$('wepKeyType').value=opt.value;
			}
			else {
				$('encryption').value=opt.value;
			}
			opt.selected="selected";
		}
           // alert(opt.text+'======='+opt.value);
		try {
		        dataEnc.add(opt, null); // standards compliant; doesn't work in IE
		}
		catch(ex) {
                       	dataEnc.add(opt); // IE only
		}
	}
	for (var k=0; k < obj.length; k++) {
		if (obj[k].getAttribute("mode")==mode && dataEnc.value != 0) {
			showNenable(obj[k]);
		}
		else if ((mode=='2' || mode == '4') && obj[k].getAttribute("mode")=='4') {
			showNenable(obj[k]);
		}
		else {
			hideNdisable(obj[k]);
		}
	}

}

function setEncryption(val,mode)
{
	if (val > 8) {
		if ($('wepKeyType').disabled == true)
			$('wepKeyType').disabled=false;
		$('wepKeyType').value=val;
		if (mode == 0) {
			$('encryption').value='1';
		}
	}
	else {
		$('wepKeyType').disabled=true;
		$('encryption').value=val;
	}
	if (val >= 64) {
		gen_11g_keys();
	}
}

function hide(objList)
{
	for (var i=0; i < objList.length; i++)
	{
		objList[i].style.display="none";
		if (objList[i].style.visibility=="hidden")
			objList[i].style.visibility="visible";
	}
}

function show(objList)
{
	for (var i=0; i< objList.length; i++)
	{
		objList[i].style.display="block";
		if (objList[i].style.visibility=="visible")
			objList[i].style.visibility="hidden";
	}
}

function enable11nFields(mode, id)
{
    id = String(id);
	if (config.MODE11N.status) {
		if ($('datarate11n' + id) != undefined) {
			if (mode == 'hide') {
                                if(config.EXT_CHAN.status && id==1){
                                    hideTR(new Array($('bandwidth11n' + id), $('gi11n' + id), $('mcsrate11n' + id), $('extProtSpacRow_' + id), $('extChanOffsetRow_' + id)));
                                }else{
                                    hideTR(new Array($('bandwidth11n' + id), $('gi11n' + id), $('mcsrate11n' + id)));
                                }
				showTR(new Array($('datarate11n' + id)));
			}
			else {
                                if(config.EXT_CHAN.status && id==1){
                                    if(($('Bandwidth' + id) != undefined) &&  $('Bandwidth' + id).value == 0){
                                       showTR(new Array($('bandwidth11n' + id), $('gi11n' + id), $('mcsrate11n' + id)));
                                    }else{
                                        
                                      showTR(new Array($('bandwidth11n' + id), $('gi11n' + id), $('mcsrate11n' + id), $('extProtSpacRow_' + id), $('extChanOffsetRow_' + id)));
                                       
                                    }
                                }else{
                                    showTR(new Array($('bandwidth11n' + id), $('gi11n' + id), $('mcsrate11n' + id)));
                                }
				hideTR(new Array($('datarate11n' + id)));
			}
		}
	}
}

function hideTR(objList)
{
	for (var i=0; i < objList.length; i++)
	{
		objList[i].style.display="none";
              enableInputFields(objList[i],false)
		objList[i].disabled=true;
	}
}

function showTR(objList)
{
	for (var i=0; i< objList.length; i++)
	{
		try {
		     objList[i].style.display="table-row";
		}
		catch(ex) {
		//  alert(ex);
                     objList[i].style.display="block";
		}
		objList[i].disabled=false;

	}
}
function calpos(obj,x)
{
}

function toggleSshEnabled(opt)
{
}

function toggleSnmpEnabled(opt)
{
}

function hideNdisable(obj)
{
	obj.style.display="none";
	obj.disabled = true;
	$(obj).descendants().each(function (element) {
		element.disabled = true;
	});
        if(obj.getElementsByTagName('td')[1].getElementsByTagName('INPUT')[0] != undefined){
            obj.getElementsByTagName('td')[1].getElementsByTagName('INPUT')[0].disabled=true;
        }
}

function showNenable(obj)
{
	try {
		obj.style.display="table-row";
	}
	catch(e) {
		obj.style.display="block";
	}
	obj.disabled = false;
	$(obj).descendants().each(function (element) {
		element.disabled = false;
	});
        if(obj.getElementsByTagName('td')[1].getElementsByTagName('INPUT')[0] != undefined){
            obj.getElementsByTagName('td')[1].getElementsByTagName('INPUT')[0].disabled=false;
        }
}

function MacAddrEdit(event, obj)
{
	var KeyValue = event.keyCode;
	var str = String.fromCharCode(KeyValue);
	var maskAlp = /^([a-fA-F])*$/i
	var maskNum = /^([0-9])*$/i
	if (KeyValue == 0x08) {       // BackSpace
		if (posit > 0) {
			posit=posit-1;
		}
		return;
	}
	if (KeyValue == 0x09 ||     // Tab
		KeyValue == 0x2e        // Delete
	) {
		return;
	}
	if (!maskAlp.test(str) && !maskNum.test(str) ) {
		event.returnValue = false;
		return false;
	}
	if (maskNum.test(str) && event.shiftKey) {
		event.returnValue = false;
		return false;
	}

/*	if ((KeyValue < 0x30 || KeyValue > 0x39) &&
		//(KeyValue < 0x60 || KeyValue > 0x69) && // numbers
		(KeyValue < 0x41 || KeyValue > 0x46)    // 'a' to 'f' or 'A' to 'F'
	) {
		event.returnValue = false;
		return false;
	}*/

		var t = document.selection.createRange();
		var vt = t.text;
		if (vt != '' && vt.length == 2 && obj.value.length == 2 && t.parentElement() == obj) {
			obj.value = '';
		}
	if (obj.value.length == 2) {
		$(obj.nextid).focus();
	}
}

function toggleChilds(mode)
{
	var inputs = fetchInputFieldsInTable('mode');
	for (var b = 0; b < inputs.length; b++) {
		if (inputs[b].id != obj.id)
			inputs[b].disabled = true;
	}
}


function getCheckedRadioValue(name) {
		var rVal;
	var radioButtons = document.getElementsByName(name);
	for (var x = 0; x < radioButtons.length; x ++) {
		if (radioButtons[x].checked) {
			rVal = radioButtons[x].value;
		}
	}
		return rVal;
}

function getCheckedRadio(name) {
		var rad;
	var radioButtons = document.getElementsByName(name);
	for (var x = 0; x < radioButtons.length; x ++) {
		if (radioButtons[x].checked) {
			rad = radioButtons[x];
		}
	}
		return rad;
}

function getCheckedRadioById(id) {
		var rad;
	$RD(id).each (function(radio) {
		if (radio.checked) {
			rad = radio;
		}
	});
		return rad;
}
var radioValChanged = true;
function setRadioCheckById(id, val, setVal) {
    $RD(id).each (function(radio) {
    	radio.checked = (parseInt(radio.value)==parseInt(val));
		if (parseInt(radio.value)==parseInt(val)) {
			radio.value = (setVal==undefined)?val:setVal;
			radioValChanged = (setVal==undefined)?false:true;
		}
	});
}

var returnVal = true;

function setApMode(val, id)
{
	var iface = (id == undefined)?$('currentInterfaceNum').value:id;
	if ((($('apMode' + iface).value == 5 || $('apMode' + iface).value == '5')) && ($S('macClone'+iface) == 1 && $('macCloneAddr'+iface).value == '' && val != 0)) {
		$RD('wdsMode'+iface).each(function(radio) {
			if (radio.value == $('apMode' + iface).value) {
				radio.checked=true;
			}
			else {
				radio.checked=false;
			}
		});
		if (config.CLIENT.status) {
			$('macCloneAddr'+iface).focus();
			$('macCloneAddr'+iface).blur();
		}
		returnVal = false;
		return false;
	}
	else{
		$('apMode'+iface).value=val;
		returnVal = true;
	}
}

function enableProfiles(flag,id)
{
	var iface = (id == undefined)?$('currentInterfaceNum').value:id;
	var inputs = Form.getElements(document.dataForm);
	for (var k=0;k < inputs.length; k++)
	{
		if (inputs[k].id != 'cb_wdsEnabled'+iface) {
			inputs[k].disabled=!flag;
			if (inputs[k].dependent) {
				if (flag)
					$(inputs[k].dependent).disabled=!inputs[k].checked;
				else
					$(inputs[k].dependent).disabled=true;
			}
		}
	}
}

function checkWDSStatus(obj, id)
{
	if (typeof(obj)!='object') {
		delete obj;
		var obj = window.top.frames['master'].$('ChannelList'+((id=='1')?'2':'1'));
	}
	if (obj.value == '0' && eval('typeof(disableChannelonWDS'+id+') == "boolean"'))
	{ 
		showMessage('Channel cannot be set to Auto with Wireless Bridge enabled!');
        setActiveContent(false);
		return false;
	}
	else {
		hideMessage();
		return true;
	}
}

function checkSNMPStatus(obj,flag)
{
	graysomething(obj,flag);
	if (obj.value == '1' && eval('typeof(snmpOnStatus) == "boolean"')) {
		showMessage('TR 069 cannot be enabled with SNMP enabled!');
		setActiveContent(false);
		activateCancel();
		return false;
	}
	else {
		hideMessage();
		return true;
	}
}

function checkTR069Status(obj,flag)
{
	graysomething(obj,flag);
	if (obj.value == '1' && eval('typeof(tr069OnStatus) == "boolean"')) {
		showMessage('SNMP cannot be enabled with TR 069 enabled!');
		setActiveContent(false);
		activateCancel();
		return false;
	}
	else {
		hideMessage();
		return true;
	}
}

function getDefaultApMode()
{
				//Purpose :- This function returns the default apMode based on the
				//           first default tab that comes up on the WDS page.
				var returnMode = '';
				if(config.P2P.status){
								returnMode = '2';
				}
				else if(config.P2MP.status) {
								returnMode = '4';
				}
				else if(config.CLIENT.status) {
								returnMode = '5';
				}

				return returnMode;
}

function integralityOnEnable() {
	if ($('gateway') != undefined && ($('gateway').value == '' && $('cb_networkintegrality').checked)) {
			showMessage('Integrity check cannot be enabled with default gateway empty!');
			$('cb_networkintegrality').checked = false;
			$('networkintegrality').value = "0";
			setActiveContent(false);
			return false;
	}
	else {
			hideMessage();
	}
}

function checkNICStatus(obj)
{
	if (config.NETWORK_INTEGRALITY.status) {
		if ($('gateway').value == '' &&  $('cb_networkintegrality').checked) {
			showMessage('Gateway cannot be empty with Network Integrality enabled!');
			setActiveContent(false);
			obj.focus();
			return false;
		}
		else {
            hideMessage();
		}
	}
}

function showHelp(tlt,hlpurl)
{
	var w='550', h='500';
	features="resizable=yes";
	var winl = (screen.width-w)/2; var wint = (screen.height-h)/2;
	if (winl < 0) winl = 0;
	if (wint < 0) wint = 0;
	var settings = 'height=' + h + ',';
	settings += 'width=' + w + ',';
	settings += 'top=' + wint + ',';
	settings += 'left=' + winl + ',';
	settings += features;
	win = window.open("help/help.html#"+hlpurl,hlpurl,settings);
	win.window.focus();
}

var win = null;

function showHelpwindow()
{
//	alert('help_'+window.opener.$('helpURL').value+'.html#'+window.name);
	document.getElementById('maincontent').contentWindow.location.replace('help_'+window.opener.$('helpURL').value+'.html#'+window.name);
}

function deleteRows(id, index)
{
	var table = $(id);
	var j=0;
	var flag = false;
	var deletedMacs=new Array();
	var tmpText = '';
	for (var i=0;i<table.rows.length;i++)
	{
		var rowObj=table.rows[i].cells[0].childNodes;
		for (var k=0; k < rowObj.length; k++)
		{
			if (rowObj[k].tagName == 'INPUT') {
				if (rowObj[k].checked == true) {
					if(table.rows[i].id!='headRow') {
						try {
							tmpText = table.rows[i].cells[1].innerText.toUpperCase();
						}
						catch(err) {
							tmpText = table.rows[i].cells[1].textContent.toUpperCase();
						}
						deletedMacs.push(tmpText);
						//deletedMacs.push(table.rows[i].cells[1].innerText);
						table.deleteRow(i);
						j++;
						i=i-1;
					}
					else {
						rowObj[k].checked=false;
					}
					flag = true;
				}
			}
		}
	}
	if (!flag) {
		alert('No rows selected!');
		return false;
	}

	$('rowCount'+index).value=parseInt($('rowCount'+index).value)-j;
	deletedMacs.each (function(mac) {
		if ($('addedAPs'+index) != undefined) {
			var str = $('addedAPs'+index).value.toUpperCase();
			var flag = str.match(mac);
			var string = str.replace('\''+mac+'\',','');
			$('addedAPs'+index).value = string;
			if (!flag)
				$('deletedAPs'+index).value = $('deletedAPs'+index).value + '\''+mac+'\',';
		}
		else if ($('addedStations'+index) != undefined) {
			var str = $('addedStations'+index).value.toUpperCase();
			var flag = str.match(mac);
			var string = str.replace('\''+mac+'\',','');
			$('addedStations'+index).value = string;
			if (!flag)
				$('deletedStations'+index).value = $('deletedStations'+index).value + '\''+mac+'\',';
		}
	});
	$('div_'+id).style.height = ($(id).rows.length > 5)?'140px':'auto';
	reAlternateLines(id);
	setActiveContent();
}

function getDefaultMode(band)
{
	var mode = '';
	if (band != undefined) {
		if (band == 'TWO') {
			mode = (config.MODE11G.status)?((config.MODE11N.status)?'2':'1'):'0';
		}
		else if (band == 'FIVE') {
			mode = (config.MODE11N.status)?'4':'3'
		}
	}
	else {
		if (config.TWOGHZ.status) {
			mode = (config.MODE11G.status)?((config.MODE11N.status)?'2':'1'):'0';
		}
		else if (config.FIVEGHZ.status) {
			mode = (config.MODE11N.status)?'4':'3'
		}
	}
	return parseInt(mode);
}

function enableFields(mode,actMode,curMode)
{
	if (config.DUAL_CONCURRENT.status) {
		enableFields_DC(mode,actMode,curMode);
	}
	else {
		enableFields_DC(mode,actMode,curMode);
	}
}

function getAllInputFields(parentObj)
{
	var elements = fetchObjectsByTagName(["INPUT","SELECT"],parentObj);
	return elements;
}

function enableInputFields(parentObj,flag, exclude)
{
	var inputs = getAllInputFields(parentObj);
	for (var j = 0; j < inputs.length; j++) {
		if (!(exclude != undefined && searchString(exclude,inputs[j].id))) {
			inputs[j].disabled = !flag;
        }
    }
}

function searchString(search,input)
{
	var result = false;
	search.each(function(str) {
		if (input.indexOf(str)!= -1) {
			result = true;
			return;
		}
	});
	return result;
}

function enableFields_ORG(mode,actMode)
{
				var inputs=fetchAllInputFields();
	var fieldsList1 = ["idbroadcastSSID1","ChannelList1","DatarateList1","MCSrateList1","Bandwidth1","GI1","PowerList1"];
	var fieldsList2 = ["idbroadcastSSID2","ChannelList2","DatarateList2","MCSrateList2","Bandwidth2","GI2","PowerList2"];
	for (var a = 0; a < inputs.length; a++)
	{
		if (inputs[a].id != 'WirelessMode1' && inputs[a].id != 'WirelessMode2' && actMode != undefined) {
		//if ($('activeMode').value != $S('WirelessMode1') && (inputs[a].id == 'chkRadio0' || inputs[a].id == 'chkRadio1'))
			if (actMode.value == mode) {
				if (inputs[a].id == 'cb_chkRadio0' || inputs[a].id == 'cb_chkRadio1') {
					//inputs[a].checked = true;
					if (!config.DUAL_CONCURRENT.status) {
						if (mode == '3'||mode == '4') {
							if (inputs[a].id == 'cb_chkRadio0') {
																																$(inputs[a].id.replace('cb_','')).value='0';
								inputs[a].checked = false;
							}
						}
						else if (inputs[a].id == 'cb_chkRadio1') {
							$(inputs[a].id.replace('cb_','')).value='0';
							inputs[a].checked = false;
						}
					}
					inputs[a].disabled = false;
				}
			}
			else if (actMode.value == '') {
				if (inputs[a].id == 'cb_chkRadio0' || inputs[a].id == 'cb_chkRadio1') {
					inputs[a].checked = false;
					inputs[a].disabled = false;
				}
				else {
					inputs[a].disabled = true;
				}
			}
			else {
				if (inputs[a].id == 'cb_chkRadio0' || inputs[a].id == 'cb_chkRadio1') {
					inputs[a].checked = false;
					$(inputs[a].id.replace('cb_','')).value='0';
				}
				else
					inputs[a].disabled = true;
			}
		}
		if ((actMode != undefined) && (actMode.value == mode)) {
			if (config.TWOGHZ.status) {
				if ($('apMode0') != undefined && $('apMode0').value == 5) {
					fieldsList1.each( function(id) {
						if (id == inputs[a].id)
							inputs[a].disabled=true;
					});
				}
				else {
					inputs[a].disabled = false;
				}
			}
			if (config.FIVEGHZ.status) {
				if ($('apMode1') != undefined && $('apMode1').value == 5) {
					fieldsList2.each( function(id) {
						if (id == inputs[a].id)
							inputs[a].disabled=true;
					});
				}
				else {
					inputs[a].disabled = false;
				}
			}
		}
	}
	//setActiveContent();
}

function checkMacAlreadyExists(tableId, macId)
{
	var mainTable=$(tableId);
	var tmpText = '';
	for (var i=0;i<mainTable.rows.length;i++) {
		try {
					tmpText = mainTable.rows[i].cells[1].innerText.toUpperCase();
		}
		catch(err) {
				tmpText = mainTable.rows[i].cells[1].textContent.toUpperCase();
		}
		if (tmpText == macId.toUpperCase()) {
			return false;
		}
	}
	return true;
}

function addMacRow(macId, id)
{
    if (id == undefined)
        var id = $('currentInterfaceNum').value;
	
	if (!checkMacAlreadyExists('trustedTable'+id, macId)) {
		alert("MAC Address already exists!")
		return false;
	}
	tableObj=$('trustedTable'+id);
	e=document.createElement("tr");

	f=document.createElement("td");
	var classString='';
	if (($('rowCount'+id).value % 2)==1)
		classString="Alternate";

	f.className=classString;
	objtxt=document.createElement("input");
	objtxt.setAttribute("type","checkbox");
	objtxt.setAttribute("id","trustStation"+id);

	f.appendChild(objtxt);
		e.appendChild(f);
//For Delete Image
	f=document.createElement("td");
	if (($('rowCount'+id).value % 2)==1)
		classString="Alternate";

	f.className=classString;
	f.innerHTML = macId;

	//f.appendChild(objtxt);

	e.appendChild(f);
	try {
		rowAdded = tableObj.childNodes[1].appendChild(e);
	}
	catch(err) {
		rowAdded = tableObj.childNodes[0].appendChild(e);
	}

	new Effect.Highlight(rowAdded, {keepBackgroundImage:true});

	$('addedStations'+id).value = $('addedStations'+id).value+'\''+macId+'\',';

	$('rowCount'+id).value=parseInt($('rowCount'+id).value)+1;
	//alert(tableObj.innerHTML);
	setActiveContent();
	return true;
}

function addTrustedStation(list, id, event){
	var ev = getEvent(event);
	var macId = '';
	if (inputForm && inputForm.formLiveValidate()) {
		//var id = ($('activeMode').value == '3' || $('activeMode').value == '4') ? 1 : 0;

		if (list) {
			var table = $('avblStationList' + id);
			var flag = false;
			for (var i = 0; i < table.rows.length; i++) {
				var rowObj = table.rows[i].cells[0].childNodes;
				for (var k = 0; k < rowObj.length; k++) {
					if (rowObj[k].tagName == 'INPUT') {
						if (rowObj[k].checked == true && table.rows[i].id != 'headRow') {
							//alert(rowObj[k].checked);
							macId = table.rows[i].cells[2].innerHTML.toUpperCase();
							addMacRow(macId,id);
							table.deleteRow(i);
							i = i - 1;
							flag = true;
						}
						else {
							if (rowObj[k].checked) {
								rowObj[k].checked = false;
							}
						}
					}
				}
			}
			if (!flag)
				alert("Please select atleast one Station!")
		}
		else {
			macId = macId + $('addNewMac' + id).value.toUpperCase();
			if (macId == '00:00:00:00:00:00' || macId.length != 17) {
				alert("Invalid MAC Address!")
				return false;
			}
			addMacRow(macId,id);
			$('addNewMac' + id).value = '';
			//addMacWin.close();
		}
		$('div_avblStationList' + id).style.height = ($('avblStationList' + id).rows.length > 5) ? '140px' : 'auto';
		$('div_trustedTable' + id).style.height = ($('trustedTable' + id).rows.length > 5) ? '140px' : 'auto';
		reAlternateLines('avblStationList' + id);
		reAlternateLines('trustedTable' + id);
		setActiveContent();
	}
	Event.stop(ev);
}

function addKnownAP(list,id)
{
	if (list) {
		var table = $('unknownAPList'+id);
		var flag = false;
		for (var i=0;i<table.rows.length;i++)
		{
			var rowObj=table.rows[i].cells[0].childNodes;
			if (rowObj[0].tagName == 'INPUT' && rowObj[0].checked == true)	{
				if (table.rows[i].id!='headRow') {
					//alert(table.rows[i].innerHTML);
					addRow('knownAPList'+id,table.rows[i],id);
					//table.deleteRow(i);
					i=i-1;
				}
				else {
					rowObj[0].checked=false;
				}
				flag =  true;
			}
		}
		if (!flag) {
			alert("Please Select atleast one AP!")
		}
	}
	$('div_knownAPList'+id).style.height = ($('knownAPList'+id).rows.length > 5)?'140px':'auto';
	$('div_unknownAPList'+id).style.height = ($('unknownAPList'+id).rows.length > 5)?'140px':'auto';
	reAlternateLines('unknownAPList'+id);
	reAlternateLines('knownAPList'+id);
	//alert($('knownAPList').innerHTML);
	setActiveContent();
}

function addRow(tableID, rowObject, id)
{
	var tableObj = $(tableID);
	var e;
	var cnt = rowObject.cells.length;
	var tmpText = '';
	convert2KnownAP(rowObject,id);
	try {
		rowOb = tableObj.childNodes[1].appendChild(rowObject);
	}
	catch(err) {
		rowOb = tableObj.childNodes[0].appendChild(rowObject);
	}
	new Effect.Highlight(rowOb, {keepBackgroundImage:true});
	$('rowCount'+id).value=parseInt($('rowCount'+id).value)+1;

	try {
		tmpText = rowOb.cells[1].innerText.toUpperCase();
	}
	catch(err) {
		tmpText = rowOb.cells[1].textContent.toUpperCase();
	}
	$('addedAPs'+id).value = $('addedAPs'+id).value+'\''+ tmpText +'\',';
}

function convert2KnownAP(rowObj, id)
{
	inputObj=rowObj.cells[0].childNodes[0];
	inputObj.id="knownAPitem"+id;
	inputObj.checked=false;
}

function getposOffset(what, offsettype)
{
	var totaloffset=(offsettype=="left")? what.offsetLeft : what.offsetTop;
	var parentEl=what.offsetParent;
	while (parentEl!=null){
			totaloffset=(offsettype=="left")? totaloffset+parentEl.offsetLeft : totaloffset+parentEl.offsetTop;
			parentEl=parentEl.offsetParent;
	}
	return totaloffset;
}

function showLayer(obj)
{
	if ((layerDiv = $(obj).down(2)) != undefined) {
		layerDiv.style.top = ($(obj).viewportOffset().top - 38) + 'px';
		layerDiv.style.left = ($(obj).viewportOffset().left - 10) + 'px';
		layerDiv.style.display = 'block';
	}
	else if ((layerDiv = $(obj).down(1)) != undefined) {
		layerDiv.style.top = ($(obj).viewportOffset().top - 38) + 'px';
		layerDiv.style.left = ($(obj).viewportOffset().left - 10) + 'px';
		layerDiv.style.display = 'block';
	}
}

function hideLayer(obj)
{
	if ($(obj).down(2) != undefined)
		$(obj).down(2).style.display = 'none';
	else if ($(obj).down(1) != undefined)
		$(obj).down(1).style.display = 'none';
}


/*****************
	* Frames related functions....
	*/
var columntype=""
var defaultsetting=""

function getCurrentSetting()
{
	if (document.body)
		return (document.body.cols)? document.body.cols : document.body.rows;
}

function setframevalue(coltype, settingvalue)
{
	if (coltype=="rows")
		document.body.rows=settingvalue;
	else if (coltype=="cols")
		document.body.cols=settingvalue;
}

function resizeFrame(contractsetting)
{
	if (getCurrentSetting()!=defaultsetting)
		setframevalue(columntype, defaultsetting);
	else
		setframevalue(columntype, contractsetting);
}

function init()
{
	if (!document.all && !document.getElementById) return;
	if (document.body!=null){
		columntype=(document.body.cols)? "cols" : "rows";
		defaultsetting=(document.body.cols)? document.body.cols : document.body.rows;
	}
}
/**********
	* End Frames functions
	*/


function convertLeaseTime(seconds,format)
{
	var days = parseInt(seconds/86400);
	var hrs = parseInt((seconds-(days*86400))/3600);
	var mnts = parseInt((seconds-((days*86400)+(hrs*3600)))/60)
	if (format == 'days') {
		return days;
	}
	else if (format == 'hours') {
		return hrs;
	}
	else if (format == 'minutes') {
		return mnts;
	}
}
function convertLeaseTime2Seconds(days, hours, minutes)
{
	var days = $('dhcpsLeaseDays').value;
	var hours = $('dhcpsLeaseHours').value;
	var minutes = $('dhcpsLeaseMinutes').value;
	var seconds = ((days*86400)+(hours*3600)+(minutes*60));
	if (seconds < 120) {
		alert('Lease Time cannot be less than 2 minutes!');
		return false;
	}
	else if ((seconds/60) > 143999) {
		alert('Lease Time cannot be more than 143999 minutes!');
		return false;
	}
	$('dhcpsLeaseTime').value = seconds;
}
function tbhdr(tlt,hlpurl){
	str="<table class='tableStyle'><tr><td colspan='2' class='subSectionTabTopLeft spacer80Percent font12BoldBlue'>"+tlt;
	str+="</td><td class='subSectionTabTopRight spacer20Percent'><a href='javascript: void(0);' onclick=\"showHelp('"+tlt+"','"+hlpurl+"');\">";
	str+="<img src='../images/help_icon.gif' width='12' height='12' title='Click for help'/></a></td></tr><tr><td colspan='3' class='subSectionTabTopShadow'></td></tr></table>"
	document.write(str);
}


function checkNum()
{
	var carCode = event.keyCode;
	if ((carCode < 48) || (carCode > 57))
	{
		event.cancelBubble = true;
		event.returnValue = false;
	}
}

function updateStationInfo(event,mac)
{
	if (!mac || mac == -1) {
		alert('Please Select a station!');
		return false;
	}
	row = $(mac);
		for (var i=0;i < row.cells.length; i++)
	{
		if ($('layeredWinTable').rows[i]) {
			if ($('layeredWinTable').rows[i].cells[1]) {
				if ($('layeredWinTable').rows[i].cells[1].childNodes[0]) {
					$($('layeredWinTable').rows[i].cells[1]).update('<table style="width: 90px; border: 1px solid #7FB2E5; background: #FFFFFF; padding: 0px; margin: 0px;"><tr><td style="width: 90px; padding: 1px; margin: 0px;">' + row.cells[(i + 1)].innerHTML.pad(20," ",1) + '</td></tr></table>');
				}
			}
		}
	}
	if (event!=null)
	var evt = getEvent(event);
	return true;
}

function showPopupWindow()
{
	detailsPopup = window.open('','Details','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=1,width=510px, height=565px');
	detailsPopup.document.body.innerHTML = '';
	detailsPopup.document.write('<html><title>Wireless Station Details</title><head><link rel="stylesheet" href="include/css/style.css" type="text/css">'+
			'<link rel="stylesheet" href="include/css/default.css" type="text/css">' +
'</head><body style="padding: 5px;"><div id="tempDiv">'+$('layeredWindow').innerHTML+'</div></body></html>');
	detailsPopup.focus();
}

function showSurveyPopupWindow()
{
	detailsPopup = window.open('siteSurvey.php','Details','toolbar=no,scrollbars=yes,statusbar=no,menubar=no,resizable=yes,width=510px, height=450px');
	detailsPopup.focus();
}

function str_replace(search, replace, subject) {
	var f = search, r = replace, s = subject;
	var ra = r instanceof Array, sa = s instanceof Array, f = [].concat(f), r = [].concat(r), i = (s = [].concat(s)).length;
	while (j = 0, i--) {
		if (s[i]) {
			while (s[i] = (s[i]+'').split(f[j]).join(ra ? r[j] || "" : r[0]), ++j in f){};
		}
	}
	return sa ? s : s[0];
}

function copyAPDetails(row)
{
	var val = str_replace('&nbsp;',' ',row.getElementsByTagName('td')[1].innerHTML);
	$('wirelessSSID0').value = str_replace('&amp;','&',val);
	//$('wirelessSSID0').value = row.getElementsByTagName('td')[1].innerHTML.replace('&nbsp;',' ').replace('&amp;','&');
	//alert(row.getElementsByTagName('td')[1].innerHTML);
	$('authenticationType').value = row.getElementsByTagName('td')[2].getElementsByTagName('INPUT')[0].value;
	var enc = (isNaN(parseInt(row.getElementsByTagName('td')[3].getElementsByTagName('INPUT')[0].value)) || row.getElementsByTagName('td')[3].getElementsByTagName('INPUT')[0].value == '')?64:row.getElementsByTagName('td')[3].getElementsByTagName('INPUT')[0].value
	$('key_size_11g').value = (isNaN(parseInt(row.getElementsByTagName('td')[3].getElementsByTagName('INPUT')[0].value)) || row.getElementsByTagName('td')[3].getElementsByTagName('INPUT')[0].value == '')?64:row.getElementsByTagName('td')[3].getElementsByTagName('INPUT')[0].value;
	DisplayClientSettings($('authenticationType').value, enc);
	if ($('key_size_11g').value > 8) {
		$('encryption').value=1;
		$('wepKeyType').disabled=false;
		$('wepKeyType').value=$('key_size_11g').value;
	}
	//showPassphrase();
	setActiveContent();
	detailsPopup.close();
}

function showPassphrase()
{
	if ($('key_size_11g').value == 0) {
		//hide all
		$('wpa_row').style.display = "none";
		$('wep_row').style.display = "none";
		$('wpa_row').disabled = true;
		$('wep_row').disabled = true;
	}
	else if ($('key_size_11g').value == 2 || $('key_size_11g').value == 4) {
		//show wpa presharedkey
		$('wep_row').style.display = "none";
		$('wep_row').disabled = true;
		try {
			$('wpa_row').style.display = "table-row";
		}
		catch (e) {
			$('wpa_row').style.display = "block";
		}
		$('wpa_row').disabled = false;
	}
	else {
		//show wep passphrase
		$('wpa_row').style.display = "none";
		$('wpa_row').disabled = true;
		try {
			$('wep_row').style.display = "table-row";
		}
		catch (e) {
			$('wep_row').style.display = "block";
		}
		$('wep_row').disabled = false;
		gen_11g_keys();
	}
}

function DisplayClientSettings(mode,option)
{
	var obj=fetchObjectsByAttribute("mode","TR");
	var flag=false;
	if (mode=='32') {
		var List=new Array('AES');
		var ListVal=new Array('4');
		$('encryption').value='4';
		$('wepKeyType').disabled=true;
	}
	else if (mode=='16') {
		var List=new Array('TKIP');
		var ListVal=new Array('2');
		$('encryption').value='2';
		$('wepKeyType').disabled=true;
	}
	else if (mode=='1') {
		var List=new Array('64 bit WEP','128 bit WEP', '152 bit WEP');
		var ListVal=new Array('64','128','152');
		$('encryption').value='1';
		$('wepKeyType').disabled=false;
		$('wepKeyType').value='64';
	}
	else if (mode!= '0') {
		var List=new Array('None');
		var ListVal=new Array('0');
		$('encryption').value='0';
		$('wepKeyType').disabled=true;
	}
	else {
		var List=new Array('None','64 bit WEP','128 bit WEP', '152 bit WEP');
		var ListVal=new Array('0','64','128','152');
		$('encryption').value='0';
		$('wepKeyType').disabled=true;
		if (option == 64) {
			flag = true;
		}
	}


	var dataEnc = $('key_size_11g');
	var selEnc = dataEnc.value;
	do
		dataEnc.options[0] = null;
	while (dataEnc.length > 0);
	for (var j=0; j<List.length; j++)
	{
		var opt=document.createElement('OPTION');
		opt.text=List[j];
		opt.value=ListVal[j];
		if (selEnc == ListVal[j]) {
			if (opt.value > 8) {
				$('wepKeyType').value=opt.value;
			}
			opt.selected="selected";
		}
//alert(opt.text+'======='+opt.value);
		try {
			dataEnc.add(opt, null); // standards compliant; doesn't work in IE
		}
		catch(ex) {
			dataEnc.add(opt); // IE only
		}
	}
	if (flag) {
		dataEnc.value = 64;
	}
	for (var k=0; k < obj.length; k++) {
		if ((obj[k].getAttribute("mode")==mode || mode == 0) && dataEnc.value != 0) {
			showNenable(obj[k]);
		}
		else {
			hideNdisable(obj[k]);
		}
	}
	showPassphrase();
}


var x = 0;
var intervalId;
function updatePosition(item,pixel) {
	pixel = parseInt(x);
	x = x + 20;
	if (pixel > 200)
		clearInterval(intervalId);
	else
	switch(item) {
		case "left": $("progress").style.left=(pixel) + 'px'; break;
		case "top": $("progress").style.top=(pixel) + 'px'; break;
		case "width": $("progress").style.width=(pixel) + 'px'; break;
		case "height": $("progress").style.height=(pixel) + 'px'; break;
	}
}

function checkActiveSession()
{
	if (parent.parent.frames['master'].progressBar != undefined)
		parent.parent.frames['master'].progressBar.open();
	var oOptions = {
						method: "get",
			asynchronous: false,
			parameters: { checkActiveSession: 'check', id: Math.floor(Math.random()*1000001) },
						onSuccess: function (oXHR, oJson) {
				var response = oXHR.responseText;
				if(response == 'expired') {
					//alert("Session Expired!");
					window.top.location.href = "index.php";
				}
				else {
					//alert('Session Active!');
							return true;
				}
				return false;
						},
						onFailure: function (oXHR, oJson) {
				alert("There was an error with the connection, Please try again!");
				parent.parent.frames['master'].progressBar.close();
						}
				};
		var req = new Ajax.Request('checkSession.php', oOptions);
}

function displayHotspotError(obj)
{
	if (obj.value == 0) {
		$('br_head').innerHTML = 'Disable Hotspot setting first!';
		$('errorMessageBlock').show();
		setRadioCheckById(obj.id,'1');
        activateApply(false);
		return false;
	}
}

function displayDHCPSError(obj)
{
	if (obj.value == 1) {
		$('br_head').innerHTML = 'Enable DHCP Server first!';
		$('errorMessageBlock').show();
		setRadioCheckById(obj.id,'0');
        activateApply(false);
		return false;
	}
}

function resetActiveContent(){
    if(($('errorMessageBlock').style.display != 'none') && ($('br_head').innerHTML == 'Bridging cannot be enabled with channel set to Auto!')){
        activateApply(false);
    }
}
//<------------VVDN CODE START----------->
function updateCloudStatus(status)
{
	
	if (status=='0') {
		document.getElementById('hidden_cloudStatus').value = '0';	
	}
	else {
		document.getElementById('hidden_cloudStatus').value = '1';
	}
}
//<------------VVDN CODE ENDS----------->

var linktestProgress = 0;	/* variable to check whether link test is completed or not */
function doSubmit(obj, val, changed)
{
	if(val == 'apply'){
if($('clientStatus') != undefined){
                if($('clientStatus').value==1){
                        var currentMode=$('apModeTab').value;
                        if($('wdsMode0') != undefined){
                                var radioButtons = document.getElementsByName("apmod1");
                                for (var x = 0; x < radioButtons.length; x++) {
                                        if(radioButtons[x].checked && radioButtons[x].value == '5') {
                                                if(radioButtons[x].value != currentMode) {
                                                        if (!confirm("Doing this configuration may cause DHCP server to assign a new IP address after reboot. To avoid this, configure static IP address to the AP"))
                                                                return;
                                                }
                                        }
                                }
                        }
                }
        }
}

//<------------VVDN CODE START----------->

if(val == 'apply'){	
	if($('hiddenCloud') != undefined )
	{
		var PrevCloudVal = document.getElementById("hiddenCloud").value;
		var CurrCloudVal = document.getElementById("hidden_cloudStatus").value;
		document.getElementById("hiddenAP_StandaloneMode").value = '0';
		
	}
	//document.getElementById("hiddenRebootOption").value = '0';
	else if($('enableMode1') != undefined)
	{
		var PrevCloudVal = document.getElementById("enableMode1").value;
		var CurrCloudVal = document.getElementById("hidden_cloudStatus").value;
		document.getElementById("hiddenAP_StandaloneMode").value = '0';
	}
	if (PrevCloudVal == '1'  &&  CurrCloudVal == '0') 
	{
		
		if(confirm("Selecting this option will reboot the Access Point and put the Access Point in Standalone mode. All other configuration will be restored to Factory default. This option will try to remove this Access Point (Sl.No: "+ document.getElementById("serno").value +") from the cloud inventory."))
				{
						document.getElementById("hiddenAP_StandaloneMode").value = '1';
				}
		else
				{		
						return;
				}		
	}
	else if(PrevCloudVal == '0'  &&  CurrCloudVal == '1')
	{
		if(confirm("Selecting this option will enable cloud mode in Access Point, after connecting to the cloud Access Point will reboot.We highly recommend you to add Access Point Serial No(" + document.getElementById("serno").value + ") into Netgear Business Central (https://wiresless.netgear.com)"))
		{
			document.getElementById("hiddenAP_StandaloneMode").value = '2';
		}
		else
		{
			return;
		}
	}
	else
	{
	}
}
//<------------VVDN CODE ENDS----------->

       if (val == 'apply') {
       		if(linktestProgress == 1){
			alert("Link test is in progress, Try to configure it later");
			return;
		}
	}
       if (window.top.frames['master']._disableAll != undefined && window.top.frames['master']._disableAll == false) {
		top.master._disableAll = true;
	}
	if (changed != undefined) {
		if (changed == false) {
			return;
		}
	}
	else if (val == 'apply') {
		return;
	}
	if (val != 'cancel' && $('sysCountry') != undefined && $('sysCountryRegion') != undefined && $('sysCountry').value != $('sysCountryRegion').value) {
		if (!confirm("Changed Country / Region will be applied only after rebooting! This may cause the radio to get enabled if it was in disabled state!\n\nClick OK to apply the changes and reboot.")) {
			return;
		}
	}
/*BEGIN Change from Moulidaren: This will hide other tabs when the mode is changed to cloud ON. On refresh of the frame, the page will submit the form in order to display the right tab. This is applied only for the Cloud tab in the GUI.  */
	if(document.getElementById('enableUi')!=null){
		if((changed) && (val=='apply') && (document.getElementById('enableUi').value==1)){
			window.parent.location.reload();
			//if (!confirm("Cloud change will be applied only after rebooting! \n\nClick OK to apply the changes and reboot.")) {
			//	return;
			//}
		}
	}  
//END of change from Moulidaren.
	setActiveContent(false);

	//Client mode prompt for reboot
	if($('wdsMode0') != undefined){
		var radioButtons = document.getElementsByName("wdsMode0");
		for (var x = 0; x < radioButtons.length; x++) {
			if(radioButtons[x].checked && radioButtons[x].value == '5')
			{
			if (!confirm("Client changes will be applied only after rebooting! \n\nClick OK to apply the changes and reboot.")) 
			return;
		 	}
	     }
		}
    
	if ( $('firmwareFile') != undefined) {
        if((config.WN802TV2 != undefined) && (config.WN802TV2.status)){
            parent.parent.frames['master'].progressBar.open();
            $('Action').disabled = false;
            if (val == 'apply') {
                if (confirm("Requires reboot\n\nClick OK to proceed.")) {
                    $('Action').value = val;
                    new Ajax.Request("killall.php", {
                        method: 'post',
                        onComplete: function(response){
                            setTimeout("document.dataForm.submit();", 25000);
                        }
                    });
                }
                else {
                    $('Action').value = "";
                    window.top.frames['master'].document.dataForm.reset();
                    parent.parent.frames['master'].progressBar.close();
                    return;
                }
            }
            else {
                $('Action').value = "";
                window.top.frames['master'].document.dataForm.reset();
                parent.parent.frames['master'].progressBar.close();
                return;
            }
        }
        else {
            parent.parent.frames['master'].progressBar.open();
            $('Action').disabled = false;
            if (val == 'apply')
                $('Action').value = val;
            document.dataForm.submit();
        }
	}
	else if (val != 'apply') {
		if (val=='login') {
			if (inputForm && inputForm.formLiveValidate()) {
				window.top.frames['master'].progressBar.open();
				window.top.frames['master'].document.dataForm.submit();
			}
			else {
				var evt = getEvent(obj);
				evt.returnValue = false;
				return false;
			}
		}
		else {
			window.top.frames['master'].progressBar.open();
			window.top.frames['master'].document.dataForm.submit();
		}
	}
	
        else if (inputForm && inputForm.formLiveValidate()){
	      if (checkSubmit(val)){
			var flag = true;
			if (($('priRadIpAddr') != undefined) && ($('sndRadIpAddr') != undefined) && ($('priRadIpAddr').value == '') && ($('sndRadIpAddr').value != '')) {
				alert('Primary Server should be configured first!');
			}
			else if (($('priAcntIpAddr') != undefined) && ($('sndAcntIpAddr') != undefined) && ($('priAcntIpAddr').value == '') && ($('sndAcntIpAddr').value != '')) {
				alert('Primary Server should be configured first!');
			}
			else if (($('priRadIpAddr') != undefined) && ($('authType') != undefined) && (($('authType').value == '0')) ) {
			if(config.IPV6.status){
			if($('priRadIpAddr').value == '' && $('v6priRadIpAddr').value == ''){
				alert("Primary Radius server IP should not be empty when Network Authentication set to radius!!");
                Form.focusFirstElement(document.dataForm);
				}
				else 
				flag=false;
			}
			else if($('priRadIpAddr').value == ''){
				alert("Primary Radius server IP should not be empty when Network Authentication set to radius!!");
                Form.focusFirstElement(document.dataForm);
				}
				else{
				flag=false;
				}
			}
			else {
				flag = false;
			}
			if (($('activeMode')!=undefined) && $('activeMode').value == '' && !config.DUAL_CONCURRENT.status) {
                $RD('WirelessMode1').each(function(radio) {
                    radio.disabled=true;
                });
            }
            if (!flag) {
				for (var i = 0; i < document.dataForm.elements.length; i++) {
					if ((window.top.frames['master'].document.dataForm.elements[i].type != 'hidden')) {
						if ((window.top.frames['master'].document.dataForm.elements[i].getAttribute("masked") == "true")||(window.top.frames['master'].document.dataForm.elements[i].getAttribute("masked") == true)) {
							if (window.top.frames['master'].document.dataForm.elements[i].getAttribute("validate").indexOf('IpAddress') != -1) {
								if (window.top.frames['master'].document.dataForm.elements[i].value == '')
									window.top.frames['master'].document.dataForm.elements[i].value = '0.0.0.0';
							}
							else if (window.top.frames['master'].document.dataForm.elements[i].getAttribute("validate").indexOf('IpV6') != -1)                                                         {
								if (window.top.frames['master'].document.dataForm.elements[i].value == '')
									window.top.frames['master'].document.dataForm.elements[i].value = '::0';
							}
							else if (window.top.frames['master'].document.dataForm.elements[i].getAttribute("validate").indexOf('MACAddress') != -1)                                                         {
								if (window.top.frames['master'].document.dataForm.elements[i].value == '')
									window.top.frames['master'].document.dataForm.elements[i].value = '00:00:00:00:00:00';
							}
							else {
								window.top.frames['master'].document.dataForm.elements[i].disabled = true;
							}
						}
					}
				}
                if(config.DUAL_CONCURRENT.status){
                    //In case of dual concurrency, on apply, if any mode is de-activated then set its value to defaul mode.
                    //Not doing this does a config crash as null value goes in.
                    if($('activeMode1') != undefined && $('activeMode1').value == ''){
                        $('activeMode1').value = getDefaultMode('FIVE');
                    }
                    if($('activeMode0') != undefined && $('activeMode0').value == ''){
                        $('activeMode0').value = getDefaultMode('TW0');
                    }
                }
                if(config.PASSPHRASE_CHAR.status){
                    if($('wepPassPhrase')!= undefined){
                        if(($('showPassphrase1') != undefined) &&  ($S('showPassphrase1') == 0) && ($('wepPassPhrase_hidden') != undefined) && ($('wepPassPhrase_hidden').value != "")){
                            $('wepPassPhrase').value = $('wepPassPhrase_hidden').value;
                        }
                    }
                    if($('wpa_psk')!= undefined){
                        if(($('showPassphrase2') != undefined) &&  ($S('showPassphrase2') == 0) && ($('wpa_psk_hidden') != undefined) && ($('wpa_psk_hidden').value != "")){
                            $('wpa_psk').value = $('wpa_psk_hidden').value;
                        }
                    }
                }

				window.top.frames['master'].progressBar.open();
				$('Action').disabled = false;
				if (val == 'apply')
					$('Action').value = val;
                               	window.top.frames['master'].document.dataForm.submit();
			}
		}
	}
        if (config.WPS.status && $('chkWPS') != undefined) {
            if($S('chkWPS')=='0'){
                if($('disableRouterPin').value !='00'){
                    var wpsRouterPinStatus = "0";
                    if($('disableRouterPinl').checked == true){
                        wpsRouterPinStatus = "1";
                    }

                        var oOptions = {
                        method: "get",
                        asynchronous: false,
                        parameters: { action: 'routerPin', routerPinValue:wpsRouterPinStatus},
                        onSuccess: function (oXHR, oJson) {
                            var response = oXHR.responseText;
			    },
                        onFailure: function (oXHR, oJson) {
                            alert("There was an error with the process, Please try again!");
                            }
                        };
                        var req = new Ajax.Request('wpsstatus.php?id='+Math.random(10000,99999), oOptions);
                   }
            }
        }
}

function checkSubmit(val)
{
	if(val == 'apply'){	
		if (config.ARADA_IPS.status){
			if($('rogueApDetection_wlan0') != undefined && $('wlan0_ipsStatus').value == '1'){
				if (($('activeMode0')!= undefined) && ($('activeMode0').value != '')){	
					if(($('rogueApDetection_wlan0').value == '0') && 
						($('Adhocnetworkdetected_wlan0').value == '0') &&
						($('Ad-hoc-nt-wired-connec_wlan0').value == '0') && 
						($('kn_client_wlan0').value == '0') ) {
							if(!confirm("Disabling of Rogue AP detection,Ad-hoc network detected,\nAd-hoc network with wired connectivity and \nKnown client associating with ad-hoc network Attacks\n will disable the Rogue AP Detection"))
								return;		
					}
				}
			}
			if($('rogueApDetection_wlan1') != undefined && $('wlan1_ipsStatus').value == '1'){
				if (($('activeMode1')!= undefined) && ($('activeMode1').value != '')){	
					if($('rogueApDetection_wlan1').value == '0' && 
						$('Adhocnetworkdetected_wlan1').value == '0' &&
						$('Ad-hoc-nt-wired-connec_wlan1').value == '0' && 
						$('kn_client_wlan1').value == '0' ) {
							if(!confirm("Disabling of Rogue AP detection,Ad-hoc network detected,\nAd-hoc network with wired connectivity and \nKnown client associating with ad-hoc network Attacks\n will disable the Rogue AP Detection"))
								return;		
					}
				}
			}
		}
	}
	if (!config.DUAL_CONCURRENT.status) {   
        if(!config.FIVEGHZ.status){   
            if ($('activeMode')!= undefined)
                eval('var res = (typeof(disableChannelonWDS'+(($('activeMode').value=='3'||$('activeMode').value=='4')?'1':'0')+') == "boolean");');
            else
                var res = false;

            if(res){
                
                   return checkWDSStatus('',($('activeMode').value=='3'||$('activeMode').value=='4')?'1':'0');
            }
        }
        else{ 
            if (($('activeMode0')!= undefined) && ($('activeMode0').value != '')){
                var tmpRad = 0;
            
            }
            else if (($('activeMode1')!= undefined) && ($('activeMode1').value != '')){ 
                var tmpRad = 1;
            }

                if(tmpRad != undefined){
                    eval('var res = (typeof(disableChannelonWDS'+tmpRad+') == "boolean");');
                    if (typeof(obj)!='object') {
                        delete obj;
                    }
                    var obj = window.top.frames['master'].$('ChannelList'+((tmpRad=='1')?'2':'1'));
                    if (obj !=undefined && obj.value == '0' && eval('typeof(disableChannelonWDS'+tmpRad+') == "boolean"')){
                        if(($('cb_chkRadio'+tmpRad) != undefined) && ($('cb_chkRadio'+tmpRad).checked != false)){
                            showMessage('Channel cannot be set to Auto with Wireless Bridge enabled!');
                            retVal = false;
                        }
                    }
                    else{
                        retVal = true;
                    }
                    if($('cb_chkRadio'+tmpRad) != undefined){
                        return retVal;
                }
            }
        }
		if($('snmpStatus') != undefined){
			if (config.TR69.status) {
				if ($S('snmpStatus') == '1' && eval('typeof(tr069OnStatus) == "boolean"')) {
					showMessage('SNMP cannot be enabled with TR 069 enabled!');
					activateApply(false);
					return false;
				}
			}
		}
		if($('tr069Status') != undefined){
			if (config.SNMP.status) {
				if ($S('tr069Status') == '1' && eval('typeof(snmpOnStatus) == "boolean"')) {
					showMessage('TR 069 cannot be enabled with SNMP enabled!');
					activateApply(false);
					return false;
				}
			}
		}
	}
	else { 
		if ($('activeMode0')!= undefined){ 
			eval('var res = (typeof(disableChannelonWDS0) == "boolean");');
        }
		else{
			var res = false;
        }
        //return false;
        if(res != true){
            if ($('activeMode1')!= undefined){ 
                eval('var res = (typeof(disableChannelonWDS1) == "boolean");');
            }
            else{
                var res = false;
            }
        }
         
        if(res){ 
			var retVal = true;
			for(var i=0;i<2;i++) {
				if (typeof(obj)!='object') {
					delete obj;
                }
					var obj = window.top.frames['master'].$('ChannelList'+((i=='1')?'2':'1'));
				if (obj.value == '0' && eval('typeof(disableChannelonWDS'+i+') == "boolean"'))
				{
                    if (config.DUAL_CONCURRENT.status){
                        if(($('cb_chkRadio' + i) != undefined) && ($('cb_chkRadio' + i).checked != false)){
                            showMessage('Channel cannot be set to Auto with Wireless Bridge enabled!');
                            retVal = false;
                        }
                    }else{
                            showMessage('Channel cannot be set to Auto with Wireless Bridge enabled!');
                            retVal = false;
                    }
			}
				else {
					hideMessage('Click APPLY for the changes to take effect!');
				}
			}
			
			return retVal;
        }
		
 		if($('snmpStatus') != undefined){
			if (config.TR69.status) {
				if ($S('snmpStatus') == '1' && eval('typeof(tr069OnStatus) == "boolean"')) {
					showMessage('SNMP cannot be enabled with TR 069 enabled!');
					activateApply(false);
					return false;
				}
			}
		}
		
			if($('tr069Status') != undefined){
				if (config.SNMP.status) {
					if ($S('tr069Status') == '1' && eval('typeof(snmpOnStatus) == "boolean"')) {
					showMessage('TR 069 cannot be enabled with SNMP enabled!');
					activateApply(false);
					return false;
				}
			}
		}
		
	}
	if ($('currentInterfaceNum') != undefined)
		var iface = $('currentInterfaceNum').value;
	else
		var iface = '';
	if (val != 'cancel' && $('maxWirelessClients'+iface) != undefined && $('dbMaxWirelessClients'+iface) != undefined && $('dbMaxWirelessClients'+iface).value != $('maxWirelessClients'+iface).value) {
		if (!confirm("Maximum wireless clients will be applied!\n\nClick OK to apply the changes.")) {
			return false;
		}
	}
	if ((typeof(window.top.frames['master'].addMacWin) == 'object') && (window.top.frames['master'].addMacWin.isOpened() == true)) {
		alert("Please close the layered window before applying the changes!");
		setActiveContent();
		return false;
	}
        var WPSSessionValidation = true;
        if(config.WPS.status)
		{
			var WPSsessionStatus = checkWPSSession();
			if(WPSsessionStatus != 'success')
			{
				WPSSessionValidation = false;
			}
		}
		if(WPSSessionValidation == false)
		{
			alert("Can not update any parameter when WPS is going on.");		
			return false;
		}

                if(config.WPS.status)
                {
                    if($('authenticationType') != undefined){
                        var authVal = $('authenticationType').value;
                            if(authVal == '1' || authVal == '4' || authVal == '8' || authVal == '12'){
                            if($('wpsDisableStatus') != undefined && $('wpsDisableStatus').value == '0'){
                                alert("To use this Network Authentication method, you must first disable WPS functionality.");
                                return false;
                            }
                        }
                    }

                    if($('chkWPS') != undefined){
                        var wpsVal = $S('chkWPS');
                        if(wpsVal == '0'){
                            var securityStatusVal = $('secutityStatus').value;
                            if (securityStatusVal == '1' || securityStatusVal == '4' || securityStatusVal == '8' || securityStatusVal == '12'){
                                var authenticationType = '';
                                switch(securityStatusVal){
                                    case "1":
                                        authenticationType = "Shared Key";
                                        break;
                                    case "4":
                                        authenticationType = "WPA with Radius";
                                        break;
                                    case "8":
                                        authenticationType = "WPA2 with Radius";
                                        break;
                                    case "12":
                                        authenticationType = "WPA & WPA2 with Radius";
                                        break;
                                }
                                alert('Can not enable WPS functionality with Network Authentication as ' + authenticationType + '.');
                                return false;
                            }
                        }
                    }
                }

        if(config.SCH_WIRELESS_ON_OFF.status){
            if (($('radioOnHour') != undefined) && ($('radioOffHour') != undefined) && $S('schWirelessonoff') != undefined){
                if ((!validateSameTime()) && ($S('schWirelessonoff') == 1)){
                    alert('Radio ON Time and Radio OFF Time cannot be same.')
                    return false;
                }
            }
        }

                if(config.WN604.status){
                    if($('wpa_psk_2') != undefined){
                        if($('authenticationType').value == 16 || $('authenticationType').value == 32 || $('authenticationType').value == 48){
                            if($S('wpaPresharedKeyType') == '1'){
                                if($('pskTouched').value == '1'){
                                    if($('wpa_psk_2').value.length == 0){
                                        alert("WPA PSK can't be empty." );
                                        return false;
                                    }
                                    if($('wpa_psk_2').value.length < 64){
                                        alert('WPA PSK must be 64 characters long.');
                                        return false;
                                    }
                                    if(!hexValueCheck($('wpa_psk_2').value)){
                                        alert('WPA PSK should be a Hexadecimal value.');
                                        return false;
                                    }
                                }
                            }
                            else if($S('wpaPresharedKeyType') == '0'){
                                if($('passPhraseTouched').value == '1'){
                                    if($('wpa_psk').value.length == 0){
                                        alert("WPA Passphrase can't be empty." );
                                        return false;
                                    }
                                    if($('wpa_psk').value.length < 8){
                                        alert('WPA Passphrase must not be less than 8 characters long.');
                                        return false;
                                    }
                                }
                            }
                        }
                    }
                }

                if(config.EXT_CHAN.status){
                    if(($('ChannelList1')!= undefined) && ($('ChannelList1').value != 0) && ($('Bandwidth1') != undefined) && ($('Bandwidth1').value != 0)){
                        if(($('WirelessMode1') != undefined) && ($S('WirelessMode1') == 2)){
                            if(($('extProtSpec_0') != undefined) && ($('extChanOffset_0') != undefined) && ($('extChanOffset_0').value) != 0){
                                var spacingVal = 4;
                                if($('extProtSpec_0').value == 2){
                                    spacingVal = 5;
                                }
                                if(!extChanCheck($('ChannelList1').value, spacingVal, $('extChanOffset_0').value)){
                                    activateCancel();
                                    return false;
                                }
                            }
                        }
                    }
                    if(($('ChannelList1')!= undefined) && ($('ChannelList1').value == 0) && ($('Bandwidth1') != undefined) && ($('Bandwidth1').value != 0)){
                        if(($('WirelessMode1') != undefined) && ($S('WirelessMode1') == 2)){
                            if(($('extChanOffset_0') != undefined) && ($('extChanOffset_0').value != 0)){
                                        alert('Ext Channel Offset has to be Auto when channel is Auto.');
                                        return false;
                            
                            }
                            if(($('extProtSpec_0') != undefined) && ($('extProtSpec_0').value != 0) && ($('Bandwidth1') != undefined) && ($('Bandwidth1').value != 0)){
                                        alert('Ext Protection Spacing has to be Auto when channel is Auto.');
                                        return false;

                            }
                        }
                    }
                }

                    for(i= 1; i<=4; i++){
                    if(($('0'+i+'_wmmApEdcaMaxBurst')!= undefined)){
                        if(!maxBurstTXOPPass($('0'+i+'_wmmApEdcaMaxBurst'),0)){
                            return false;
                            }
                    }
                    if(($('0'+i+'_wmmStaEdcaTxopLimit')!= undefined)){
                        if(!maxBurstTXOPPass($('0'+i+'_wmmStaEdcaTxopLimit'),1))
                            return false;
                    }
                }

                for(i= 1; i<=4; i++){
                    if(($('1'+i+'_wmmApEdcaMaxBurst')!= undefined)){
                        if(!maxBurstTXOPPass($('0'+i+'_wmmApEdcaMaxBurst'),0)){
                            return false;
                            }
                    }
                    if(($('1'+i+'_wmmStaEdcaTxopLimit')!= undefined)){
                        if(!maxBurstTXOPPass($('0'+i+'_wmmStaEdcaTxopLimit'),1))
                            return false;
                    }
                }

                if(config.CENTRALIZED_VLAN.status){
                    if(($('vlanType') !=undefined) && ($('vlanType').value != 0)){
                        if(!vapCentralVlanValidation()){
                            return false;
                        }
                    }
                    if(($('accessControlMode0') !=undefined) && ($('accessControlMode0').value != 2)){
                        if(!macACLVlanValidation()){
                            return false;
                        }
                    }
                }

	return true;
}

function macACLVlanValidation(){
    for(i=0; i<8; i++){
        if(($('vlanStatusvap'+i) != undefined) && ($('vlanStatusvap'+i).value != 0)){
            if(($('securityStatusvap'+i) != undefined) && (($('securityStatusvap'+i).value != 2) && ($('securityStatusvap'+i).value != 4) && ($('securityStatusvap'+i).value != 8) && ($('securityStatusvap'+i).value != 12))){
                alert("Cannot disable Remote MAC Address Database because Dynamic VLAN of Security Profile "+(i+1)+" is dependent on it!");
                return false;
            }
        }
    }
    return true;
}

function vapCentralVlanValidation()
{
    if(($('authenticationType') != undefined ) && (($('authenticationType').value != 2) && ($('authenticationType').value != 4) && ($('authenticationType').value != 8) && ($('authenticationType').value != 12))){
        if(($('vapMacACLStatus') != undefined) && ($('vapMacACLStatus').value != 2)){
            alert("Dynamic VLAN can be set only when Radius based Authentication is available!");
            return false;
        }
    }

    return true;

}

function maxBurstTXOPPass(maxBurstVal,field){
    if(maxBurstVal.value != 0){
        if((maxBurstVal.value % 32) != 0){
            if(field == 1){
                alert("TXOP Limit value has to be a multiple of 32 between 0 and 8192.");
            }
            else{
                alert("Max. Burst value has to be a multiple of 32 between 0 and 8192.");
            }
            maxBurstVal.focus();
            maxBurstVal.select();
            activateCancel();
            return false;
        }
    }
        return true;
}
function extChanCheck(channelVal,spacingVal,offsetVal){
    var valToSearch = parseInt(channelVal) - parseInt(spacingVal);
    if(offsetVal == 1){
        valToSearch = parseInt(channelVal) + parseInt(spacingVal);
    }
    if($('ChannelList1') != undefined){
        var len = $('ChannelList1').length - 1;
        if((valToSearch >= 1) && (valToSearch <= parseInt($('ChannelList1').options[len].value))){
            return true;
        }
        alert('With this settings Extension Channel will be ' + valToSearch + '. Which is not a valid Channel.');
        return false;
    }
}

function hexValueCheck(val){
    var numericExpression = /^([0-9a-fA-F])*$/i;
    if(val.match(numericExpression)){
		return true;
	}else{
		return false;
	}

}

function setPSKTouched(){
    $('pskTouched').value = '1';
}

function setPassPhraseTouched(){
    $('passPhraseTouched').value = '1';
}


function validateSameTime(){
        var RadioOnString;
        var RadioOffString;
        var RadioOnHrstr;
        var RadioOnMinstr;
        var RadioOffHrstr;
        var RadioOffMinstr;
        if ($('radioOnHour') != undefined)
        {
                if($('radioOnHour').value.length < 2)
                                RadioOnHrstr = "0"+$('radioOnHour').value;
                else
                                RadioOnHrstr = $('radioOnHour').value;

                if($('radioOnMin').value.length < 2)
                                RadioOnMinstr = "0"+$('radioOnMin').value;
                else
                                RadioOnMinstr = $('radioOnMin').value;

                RadioOnString = RadioOnHrstr+RadioOnMinstr;

        }
        if ($('radioOffHour') != undefined)
        {
                if($('radioOffHour').value.length < 2)
                                RadioOffHrstr = "0"+$('radioOffHour').value;
                else
                                RadioOffHrstr = $('radioOffHour').value;

                if($('radioOffMin').value.length < 2)
                                RadioOffMinstr = "0"+$('radioOffMin').value;
                else
                                RadioOffMinstr = $('radioOffMin').value;

                RadioOffString = RadioOffHrstr + RadioOffMinstr
        }
        if(RadioOnString == RadioOffString){
            return false
        }
        return true;
}

var tempResponse;
var responseArray;
var failureTime=0;
var garbageValue = 0;
function getWPSstatus(wpsMethod)
{  		
		var oOptions = {
		method: "get",
		asynchronous: false,
		parameters: { action: 'associationState'},
		onSuccess: function (oXHR, oJson) {
            var response = oXHR.responseText;
			responseArray = response.split(" ");
			tempResponse = responseArray[0];
			switch(tempResponse){
					case "0":
							if(wpsMethod == 1)
								$('showbarValue').innerHTML="Please click the software or hardware button on the client<br> to start the WPS process...";
							else if(wpsMethod == 2)
								$('showbarValue').innerHTML="The Client's PIN is <b>"+ responseArray[1] +"</b> <br> Please click the software button on the client to start the<br>WPS process...";
							updateProgressBar(12);
							$('WpsSuccessOkButton').style.visibility="hidden";
							$('wpsCancelButton').style.visibility="visible";

							break;
                    case "1":
							$('showbarValue').innerHTML="Authenticating the client...";    
							updateProgressBar(24);
							$('wpsCancelButton').style.visibility="visible";
							$('WpsSuccessOkButton').style.visibility="hidden";					
                        	break;
                    case "2":
							var pinMsg="";
							if(wpsMethod == 2)
								pinMsg="The client's PIn is <b>"+ responseArray[1] +"</b><br>";
							$('showbarValue').innerHTML=pinMsg+"Sending configuration data to the client...";
							updateProgressBar(36)
							$('wpsCancelButton').style.visibility="visible";
							$('WpsSuccessOkButton').style.visibility="hidden";
                       		 break;
                    case "3":
						    $('showbarValue').innerHTML="Collecting the client's information...";
							updateProgressBar(48);
							$('wpsCancelButton').style.visibility="visible";
							$('WpsSuccessOkButton').style.visibility="hidden";
                	        break;
                   case "41":
                   case "40":
							$('showbarValue').innerHTML="Success";
							updateProgressBar(60);
							setWPSWizardPage(5, wpsMethod,tempResponse);
							$('wpsonoffimage').innerHTML="<img src='images/pushButton_On.gif'>";
							$('wpsCancelButton').style.visibility="hidden";
							$('WpsSuccessOkButton').style.visibility="visible";
							return;
                    	    break;
					/*case "41":
  							$('showbarValue').innerHTML="Success<br>Click OK to check the new wireless settings...";
  														updateProgressBar(60);
  																					setWPSWizardPage(5, wpsMethod,tempResponse);
  																												$('wpsonoffimage').innerHTML="<img src='images/pushButton_On.gif'>";
  																																			$('wpsCancelButton').style.visibility="hidden";
  																																										$('WpsSuccessOkButton').style.visibility="visible";
  																																																	return;						   
  																																																								break;*/
                                        
                                        case "50":
                                        case "51":
                                        case "52":
                                        case "53":
                                        case "53":
					case "54":
                                        case "55":
                                        case "56":
                                        case "57":                                                       
                                                    $('showbarValue').innerHTML="Failure.<br><br>Click OK to go back to the Wi-Fi Protected Setup page...";
                                                    updateProgressBar(0);
                                                    $('wpsonoffimage').innerHTML="<img src='images/pushButton_Off.gif'>";
                                                    setWPSWizardPage(4, wpsMethod,tempResponse);
                                                    $('wpsCancelButton').style.visibility="hidden";
                                                    $('WpsSuccessOkButton').style.visibility="visible";
                                                    return;
     						break;                                      
					
					default:
							$('showbarValue').innerHTML="Failure.<br>Please try again...<br><br>Click OK to go back to the Wi-Fi Protected Setup page...";
							updateProgressBar(0);
							$('wpsonoffimage').innerHTML="<img src='images/pushButton_Off.gif'>";
                                                        setWPSWizardPage(4, wpsMethod,tempResponse);
							$('wpsCancelButton').style.visibility="hidden";
							$('WpsSuccessOkButton').style.visibility="visible";	
							garbageValue = 1;
							return;
							break;											
                }
		},
		onFailure: function (oXHR, oJson) {
			alert("There was an error with the process, Please try again!");
			
			$('showbarValue').innerHTML="Failure.<br>Please try again...<br><br>Click OK to go back to the Wi-Fi Protected Setup page...";
			updateProgressBar(0);
			$('wpsonoffimage').innerHTML="<img src='images/pushButton_Off.gif'>";
			$('wpsCancelButton').style.visibility="hidden";
			$('WpsSuccessOkButton').style.visibility="visible";		
			return;						
		}
	};
	var req = new Ajax.Request('wpsstatus.php?id='+Math.random(10000,99999), oOptions);
	/*if(tempResponse == "40" || tempResponse == "41" || tempResponse == "50" ||tempResponse == "53" ||tempResponse == undefined || garbageValue =="1")
  	{
  			return;
  				}*/
          
			var t = setTimeout("getWPSstatus('" + wpsMethod + "')", 5000);
			failureTime++;
			if (wpsMethod==1 && failureTime == 24) {
				setWPSWizardPage(4, wpsMethod,1);
			}

			if(wpsMethod==2 && failureTime==48)
				setWPSWizardPage(4, wpsMethod,1);
}

function updateProgressBar(varLimit)
{
	var limit = varLimit;
	if (limit != undefined) {
		for (i=1; i <=limit; i++) {
			$('progress' + i).style.backgroundColor = 'blue';
		}
		limit++;
		for(;limit<=60;limit++)
			$('progress' + limit).style.backgroundColor = 'orange';					
	}
	
}
function updateCanceToBackend()
{
    var oOptions = {
            method: "get",
            asynchronous: false,
            parameters: { action: 'wpsCancelByUser'},
            onSuccess: function (oXHR, oJson) {
                var response = oXHR.responseText;
            },
            onFailure: function (oXHR, oJson) {
                alert("There was an error with the process, Please try again!");
            }
        };

	var req = new Ajax.Request('wpsstatus.php?id='+Math.random(10000,99999), oOptions);
    return req.responseText;
}
function updateToBackend(wpsMethod, pinVal){
    if(wpsMethod == 1){
    var oOptions = {
		method: "get",
		asynchronous: false,
		parameters: { action: 'pushBtnUpdate'},
		onSuccess: function (oXHR, oJson) {
            var response = oXHR.responseText;
            //setwpssuccessmsg(wpsfailmsg,response);
            //alert(response);
		},
		onFailure: function (oXHR, oJson) {
			alert("There was an error with the process, Please try again!");
		}
	};
    }else{
        var oOptions = {
            method: "get",
            asynchronous: false,
            parameters: { action: 'pinUpdate', pinValue: pinVal},
            onSuccess: function (oXHR, oJson) {
                var response = oXHR.responseText;
                //setwpssuccessmsg(wpsfailmsg,response);
                //alert(response);
            },
            onFailure: function (oXHR, oJson) {
                alert("There was an error with the process, Please try again!");
            }
        };
    }
	var req = new Ajax.Request('wpsstatus.php?id='+Math.random(10000,99999), oOptions);
    return req.responseText;
    
}
function loadWPSWizardPage(pageId, wpsMethod,wpsfailmsg,userPinVal)
{   var wpsPinvalue = userPinVal;
		document.getElementById(pageId).style.display = '';	
		if (pageId == 3) {
            if(wpsMethod == 1){
                updateToBackend(wpsMethod,'0');
            }
            else{
                updateToBackend(wpsMethod,wpsPinvalue);
            }
			getWPSstatus(wpsMethod);
			failureTime = 1;
			wpsOnOffButton();
		}
		if(pageId==4)		
			setwpsfailmsg(wpsMethod,wpsfailmsg);
			
		if (pageId == 5) {
			getWPSClientinfo(pageId,wpsfailmsg);			
		}		
}

function getWPSClientinfo(pageId,wpsfailmsg)
{
	var oOptions = {
		method: "get",
		asynchronous: false,
		parameters: { action: 'clientInfo'},
		onSuccess: function (oXHR, oJson) {
            var response = oXHR.responseText;			
            setwpssuccessmsg(wpsfailmsg,response);
		},
		onFailure: function (oXHR, oJson) {
			alert("There was an error with the process, Please try again!");
		}
	};
	var req = new Ajax.Request('wpsstatus.php?id='+Math.random(10000,99999), oOptions);
    return req.responseText;
}
function setWPSWizardPage(pageId,wpsMethod,wpsfailmsg,pinValue)
{
	$('wizardPageID').value = pageId;
	$('whichWPSMethod').value = wpsMethod;
	$('whichwpsfailmsg').value=wpsfailmsg;
    $('userPinValue').value = pinValue;
	window.top.frames['master'].document.dataForm.submit();	
}
function validateWPSPIN(pageId){
	var wpsPinvalue = $('wpsPin').value
	if (wpsPinvalue.length == 4 || wpsPinvalue.length == 8) {
			if (wpsPinvalue.length == 4) {
                            if(!allNumberPIN(wpsPinvalue)){
				alert("PIN can have only numeric characters");
				$('wpsPin').focus();
                                event.cancelBubble = true;
                                event.returnValue =false;
                            }
                            else{
				setWPSWizardPage(pageId, 2, 0,wpsPinvalue);
                            }
			}
		if (wpsPinvalue.length == 8) {			
			if (validateChecksum(wpsPinvalue) == true) {
				setWPSWizardPage(pageId, 2, 0,wpsPinvalue);
			}
                        else if(!allNumberPIN(wpsPinvalue)){
				alert("PIN can have only numeric characters");
				$('wpsPin').focus();
                                event.cancelBubble = true;
                                event.returnValue =false;
                            
                        }
			else {
				alert("The checksum of PIN is not correct.Please check the PIN again");
				$('wpsPin').focus();
                                event.cancelBubble = true;
                                event.returnValue =false;

			}
		}
	}
	else {
		alert("A PIN is a string of 4 or 8 digits.")
		$('wpsPin').focus();
                event.cancelBubble = true;
                event.returnValue =false;
	}
}
function allNumberPIN(PIN){
    var numericExpression = /^[0-9]+$/;
    if(PIN.match(numericExpression)){
		return true;
	}else{
		return false;
	}

}
function validateChecksum(PIN)
{
	var accum = 0;
	accum += 3 * (Math.floor(PIN / 10000000) % 10);
	accum += 1 * (Math.floor(PIN / 1000000) % 10);
	accum += 3 * (Math.floor(PIN / 100000) % 10);
	accum += 1 * (Math.floor(PIN / 10000) % 10);
	accum += 3 * (Math.floor(PIN / 1000) % 10);
	accum += 1 * (Math.floor(PIN / 100) % 10);
	accum += 3 * (Math.floor(PIN / 10) % 10);
	accum += 1 * (Math.floor(PIN / 1) % 10);
	return (0 == (accum % 10));
}
function createWPSProgressBar()
{
	var msg=" ";
	for(var i=1;i<=60;i++){
		msg=msg+"<span style='background-color:orange' id='progress"+i+"'>&nbsp;</span>&nbsp";
		}
	$('showbar').innerHTML=msg;
}
function toggleWPSButtons()
{
		if($('pinButtonStatus').value == 1 && $('pushButtonStatus').value == 1)
		{
			$('pinbutton').style.display='none';
		}
		else
		{
			if ($('pushButtonStatus').value == 1) {
				$('pushbutton').style.display = "";
				$('wpspinbutton').disabled=true;
	   			}
			else 
				$('pushbutton').style.display = "none";
		
			if ($('pinButtonStatus').value == 1) {
				$('pinbutton').style.display = "";
				$('wpspushbutton').disabled=true;
				}
			else 
				$('pinbutton').style.display = "none";			
		}
		if($('pinButtonStatus').value == 0 && $('pushButtonStatus').value == 0)
		{
			$('wpspinbutton').disabled=true;
			$('wpspushbutton').disabled=true;
		}
}

function wpsOnOffButton()
{
	if (tempResponse != 40 && tempResponse !=41 && tempResponse != 5 && tempResponse != undefined && tempResponse != 53 && tempResponse != 50 && garbageValue != 1) {
		if (x == 0) {
			$('wpsonoffimage').innerHTML ="<img src='images/pushButton_On.gif'>";
			x = 1;
		}
		else {
			$('wpsonoffimage').innerHTML = "<img src='images/pushButton_Off.gif'>";
			x = 0;
		}	
	t1 = setTimeout('wpsOnOffButton()', 500);
	}
}

function setwpsfailmsg(wpsMethod,wpsfailmsg)
{
	var xtime;
	if(wpsMethod == 2)
		xtime=4;
	if(wpsMethod == 1)
		xtime=2;	
	if (wpsfailmsg == 1) {
		$('wpsfailureredmsg').innerHTML ="WPS procedure time out.";	
		$('wpsfailuremsg').innerHTML = "Fail to add a wireless client to the network in " + xtime + " minutes.<br>Please check whether the client supports the WPS function.</b>";
                return;
	}
        if (wpsfailmsg == 52) {
		$('wpsfailureredmsg').innerHTML = "WPS procedure is cancelled by user.";
	}
        else if (wpsfailmsg == 50) {
		$('wpsfailureredmsg').innerHTML = "The client's PIN is not correct.";
	}
	else if (wpsfailmsg == 53) {
		$('wpsfailureredmsg').innerHTML = "Security Risk: detecting more than one client with push button pressed.";
	}	
        else if(wpsfailmsg == 54){
            $('wpsfailureredmsg').innerHTML = "Miscellaneous failure.";
        }
        else if(wpsfailmsg == 55){
            $('wpsfailureredmsg').innerHTML = "Bad Parameter.";
        }
        else if(wpsfailmsg == 56){
            $('wpsfailureredmsg').innerHTML = "Registrar Stopped.";
        }
        else if(wpsfailmsg == 57){
            $('wpsfailureredmsg').innerHTML = "Failure during initialization.";         
        }
        else if(wpsfailmsg == 51){
            $('wpsfailureredmsg').innerHTML ="WPS procedure time out.";
            $('wpsfailuremsg').innerHTML = "Fail to add a wireless client to the network in " + xtime + " minutes.<br>Please check whether the client supports the WPS function.</b>";
            return;

        }
        else
            $('wpsfailureredmsg').innerHTML = "Failure.";

        $('wpsfailuremsg').innerHTML = "Fail to add a wireless client to the network.";       
}

function setwpssuccessmsg(wpsfailmsg,response)
{		
	var tempInfo;
	tempInfo = response.split(",");		
	var clientName = tempInfo[0];		
	var clientMac = tempInfo[1];
	if (wpsfailmsg == 40) {
		$('wpssuccessmsg').innerHTML = "<br>The wireless client "+ clientName +" ("+ clientMac +")has<br>been added to network successfully.";
		$('wpssuccesssetup').innerHTML = "Click OK to go back to the Wi-Fi Protected Setup page...";
	}
	if (wpsfailmsg == 41) {
		$('wpssuccessmsg').innerHTML = "<br>The wireless client "+ clientName +" ("+ clientMac +")has<br>been added to network successfully<br><font color='red'>Note: The wireless settings have been changed.</font>";
		$('wpssuccesssetup').innerHTML = "Click OK to check new wireless settings...";	
		
	}
}
function setWPSSuccesspage()
{	
	if ($('wpssuccesssetup').innerHTML == "Click OK to check new wireless settings...") {
		window.top.frames['header'].menuObject.updateMenu('second','2', false);
		window.top.frames['action'].$('standardButtons').show();
	}	
	if($('wpssuccesssetup').innerHTML == "Click OK to go back to the Wi-Fi Protected Setup page...")
		setWPSWizardPage(1,0,0);
}
function checkWPSSession()
{	var response="";
	var oOptions = {
		method: "get",
		asynchronous: false,
		parameters: { action: 'getWPSSession'},
		onSuccess: function (oXHR, oJson) {
             response = oXHR.responseText;						

		},
		onFailure: function (oXHR, oJson) {
			alert("There was an error with the process, Please try again!");
		}
	};
	var req = new Ajax.Request('wpsstatus.php?id='+Math.random(10000,99999), oOptions);
    //return req.responseText;
    	return response;
}
function convertRadioOnOffTime()
{
        var RadioOnTime=$('radioOnTime').value;
        var RadioOffTime=$('radioOffTime').value;
        var RadioOnHr = Math.floor(RadioOnTime/100);
        var RadioOnMin = RadioOnTime%100;
        var RadioOffHr =Math.floor(RadioOffTime/100);
        var RadioOffMin = RadioOffTime%100;

        if(RadioOnMin == 0)
        RadioOnMin="00";
        if(RadioOffMin == 0)
        RadioOffMin = "00";
        if ($('radioOnHour') != undefined) {
                $('radioOnHour').value = RadioOnHr;
                $('radioOnMin').value = RadioOnMin
        }
        if ($('radioOffHour') != undefined) {
                $('radioOffHour').value = RadioOffHr;
                $('radioOffMin').value = RadioOffMin;
        }
}

function convert2TimeString(){
        var RadioOnString;
        var RadioOffString;
        var RadioOnHrstr;
        var RadioOnMinstr;
        var RadioOffHrstr;
        var RadioOffMinstr;
        if ($('radioOnHour') != undefined)
        {
                if($('radioOnHour').value.length < 2)
                                RadioOnHrstr = "0"+$('radioOnHour').value;
                else
                                RadioOnHrstr = $('radioOnHour').value;

                if($('radioOnMin').value.length < 2)
                                RadioOnMinstr = "0"+$('radioOnMin').value;
                else
                                RadioOnMinstr = $('radioOnMin').value;

                RadioOnString = RadioOnHrstr+RadioOnMinstr;

                $('radioOnTime').value = RadioOnString;

        }
        if ($('radioOffHour') != undefined)
        {
                if($('radioOffHour').value.length < 2)
                                RadioOffHrstr = "0"+$('radioOffHour').value;
                else
                                RadioOffHrstr = $('radioOffHour').value;

                if($('radioOffMin').value.length < 2)
                                RadioOffMinstr = "0"+$('radioOffMin').value;
                else
                                RadioOffMinstr = $('radioOffMin').value;

                RadioOffString = RadioOffHrstr + RadioOffMinstr
                $('radioOffTime').value = RadioOffString;

        }
}

function disableSchduleControls(obj)
{
        if (obj.value == 0) {
                $('radioOffMon').disabled = true;
                $('radioOffTue').disabled = true;
                $('radioOffWed').disabled = true;
                $('radioOffThr').disabled = true;
                $('radioOffFri').disabled = true;
                $('radioOffSat').disabled = true;
                $('radioOffSun').disabled = true;
                $('radioOnTime').disabled = true;
                $('radioOffTime').disabled = true;
        }
        else {
                $('radioOffMon').disabled = false;
                $('radioOffTue').disabled = false;
                $('radioOffWed').disabled = false;
                $('radioOffThr').disabled = false;
                $('radioOffFri').disabled = false;
                $('radioOffSat').disabled = false;
                $('radioOffSun').disabled = false;
                $('radioOnTime').disabled = false;
                $('radioOffTime').disabled = false;
        }
}

function disableWMMPS(obj)
{
        if (obj.value == 0) {
            $RD('idwmmPowersave0').each (function(input) {
                    input.disabled=true;
            });
                //$('idwmmPowersave0').disabled = true;
                //alert('in if');
        }
        else {
            $RD('idwmmPowersave0').each (function(input) {
                    input.disabled=false;
            });
                //alert('in else');
        }
}
function disableWMMPS1(obj)
{
		if (obj.value == 0) {
            $RD('idwmmPowersave1').each (function(input) {
                    input.disabled=true;
            });
        }
        else {
            $RD('idwmmPowersave1').each (function(input) {
                    input.disabled=false;
            });
        }
}
function toggleAPPINDisplay(){
    if($('disableRouterPinl').checked){
        $('apPinLabel').disabled=true;
        $('apPinLabel').style.color="grey";
    }
    else{
        $('apPinLabel').disabled=false;
        $('apPinLabel').style.color="black";
    }
    
}
function setDisableRouter()
{
    var disableRouterPinValue =$('disableRouterPin').value;
     if($S('chkWPS') == '0'){
        if($('disableRouterPinl').checked){
            $('apPinLabel').disabled=true;
            $('apPinLabel').style.color="grey";
        }
        else{
            $('apPinLabel').disabled=false;
            $('apPinLabel').style.color="black";
        }
         $('keepExisting').disabled=false;
         switch(disableRouterPinValue)
            {
                case "00":
                     $('disableRouterPinl').disabled=true;
                    break;
                case "01":
                    $('disableRouterPinl').disabled=false;
                    break;
                case "10":
                    $('disableRouterPinl').disabled=false;
                    break;
                default:
                    $('disableRouterPinl').disabled=false;
                    break;
            }
     }
     else{
         $('apPinLabel').disabled=true;
         $('apPinLabel').style.color="grey";
         $('keepExisting').disabled=true;
     }

$('disableRouterPin').disabled = true;
}
function toggleWPAMethods(wpaType){
    if(wpaType == undefined){
        wpaType = $S('wpaPresharedKeyType');
    }
        if(wpaType == '0'){
            $('wpa_psk').disabled = false;
            $('wpa_psk_2').disabled = true;
        }else if(wpaType == '1'){
            $('wpa_psk').disabled = true;
            $('wpa_psk_2').disabled = false;

        }

}
function showPassPhrase(value,id){
    if(id == "showPassphrase1"){
        if(value == 1){
            $('wepPassPhrase').value = $('wepPassPhrase_hidden').value;
        }
        if(value == 0){
          $('wepPassPhrase_hidden').value = $('wepPassPhrase').value;
          var len = $('wepPassPhrase_hidden').value.length;
          if(len >0 ){
              $('wepPassPhrase').value = "";
              for(i = 0; i<= len; i++){
                  $('wepPassPhrase').value += '*';
              }
          }
      }
    }

    if(id == "showPassphrase2"){
        if(value == 1){
            $('wpa_psk').value = $('wpa_psk_hidden').value;
        }
        if(value == 0){
          $('wpa_psk_hidden').value = $('wpa_psk').value;
          var len = $('wpa_psk_hidden').value.length;
          if(len >0 ){
              $('wpa_psk').value = "";
              for(i = 0; i<= len; i++){
                  $('wpa_psk').value += '*';
              }
          }
      }
    }
}

function convertScheduleWeekStatus(){

	var arr = $('scheduledWirelessWeeklyStatus').value.split("");
    for(i=0; i<7; i++){
        if(arr[i] == 1)
            $('schRad_'+i).checked = true;
    }
}

function convertWeekScheduleToString(){
    $('scheduledWirelessWeeklyStatus').value = "";
    for(i=0; i<7; i++){
        if($('schRad_'+i).checked == true)
            $('scheduledWirelessWeeklyStatus').value += '1'
        else
            $('scheduledWirelessWeeklyStatus').value += '0'
    }
}

function updateDynVLANControls(){
    if(config.CENTRALIZED_VLAN.status){

        if($('vlanType').value == 0){
            $('vlan_id').disabled = false;
            $RD('vlanAccessControl').each (function(input) {
                input.disabled=false;
            });
            $RD('vlanAccessControlPolicy').each (function(input) {
                input.disabled=false;
            });

        }else{
            $('vlan_id').disabled = true;
            $RD('vlanAccessControl').each (function(input) {
                input.disabled=true;
            });
            $RD('vlanAccessControlPolicy').each (function(input) {
                input.disabled=true;
            });

        }

    }

}


function doSave_MacAuth(id){
    var table = $('trustedTable' + id);
    var strToSend = '';
    for (var i = 2; i < table.rows.length; i++) {
        strToSend += $('trustedTable' + id).rows[i].cells[1].innerHTML;
        strToSend += ',';
    }
   $('ApList').value = strToSend;

}

var linktestfailureTime=0;	/* variable to check the link test failure time*/
var linkgarbageValue = 0;	/* variable for validation */
var linktestcomplete = 0;	/* variable to check whether link test is completed or not */

/*starting the link test */

function startLinkTest(){
   if($('remoteMacAddress').value == ''){
     alert('Remote MAC Address cannot be empty');
    }
   else{
    var dbString = '';
    var myRegExp = /wlan0/;
    var curMode =''
    var match = $('wdsProfileName').name.search(myRegExp);
	/* If link test button is pressed then diactivate the link test button */
    $('linktest').disabled = true;
    linktestProgress = 1;	
    if(match != -1){
        curMode = 'wlan0';
    }else{
        curMode = 'wlan1';
    }
    var curWDSProfile = $('wdsProfileName').name.substring(51,55);
    dbString += "system:wdsSettings:wdsSettingTable:" + curMode + ":"+curWDSProfile+":wdsLinkTest 1 " + "\n";
	if ( $('linkIpAddr').value == "" ) {
    	dbString += "system:wdsSettings:wdsSettingTable:" + curMode + ":"+curWDSProfile+":linkTestIP 0.0.0.0 \n";
    	} else {
		dbString += "system:wdsSettings:wdsSettingTable:" + curMode + ":"+curWDSProfile+":linkTestIP " + $('linkIpAddr').value + "\n";
	}
    dbString += "system:wlanSettings:wlanSettingTable:" + curMode + ":apMode " + $('wdsLinkMode').value + "\n";
    if($('wdsProfileName').value != '')
    	dbString += "system:wdsSettings:wdsSettingTable:" + curMode + ":"+curWDSProfile+":wdsProfileName " + $('wdsProfileName').value + "\n";
    else{
	alert("wdsProfileName can't be empty");
	return 0;
    }
    dbString += "system:wdsSettings:wdsSettingTable:" + curMode + ":"+curWDSProfile+":remoteMacAddress " + $('remoteMacAddress').value + "\n";
    dbString += "system:wdsSettings:wdsSettingTable:" + curMode + ":"+curWDSProfile+":wdsAuthenticationtype " + $('authenticationType').value + "\n";
    /* if the encryption is WEP type with 64 or 128 or 152 type wepkey then swapping the values */ 
    if ($('key_size_11g').value > 8) {
                $('encryption').value=1;
                $('wepKeyType').value=$('key_size_11g').value;
    dbString += "system:wdsSettings:wdsSettingTable:" + curMode + ":"+curWDSProfile+":wdsEncryption " + $('encryption').value + "\n";
    dbString += "system:wdsSettings:wdsSettingTable:" + curMode + ":"+curWDSProfile+":wdsWepKeyType " + $('key_size_11g').value + "\n";
    }
    else
    	dbString += "system:wdsSettings:wdsSettingTable:" + curMode + ":"+curWDSProfile+":wdsEncryption " + $('key_size_11g').value + "\n";
    if($('authenticationType').value != 0){
	/* Checking whether the wps_psk is in visible(text type) form or invisible(star type) form */
	if(checkInputKeys($('wpa_psk').value))
        	dbString += "system:wdsSettings:wdsSettingTable:" + curMode + ":"+curWDSProfile+":wdsPresharedkey " + $('wpa_psk').value + "\n";
	else{
		alert("The wdsPresharedkey should be in visiable form");
	    	$('linktest').disabled = false;
    		linktestProgress = 0;	
		return 0;
	}
    }
    if($('key_size_11g').value == '64' || $('key_size_11g').value == '128' || $('key_size_11g').value == '152'){
	/* Checking whether the wdsWepKey is in visible(text type) form or invisible(star type) form */
	if(checkInputKeys($('wepKey').value)){
        	dbString += "system:wdsSettings:wdsSettingTable:" + curMode + ":"+curWDSProfile+":wdsWepPassPhrase " + $('wepPassPhrase').value + "\n";
        	dbString += "system:wdsSettings:wdsSettingTable:" + curMode + ":"+curWDSProfile+":wdsWepKey " + $('wepKey').value + "\n";
	}
	else{
		alert("The wdsWepKey should be in visiable form");
	    	$('linktest').disabled = false;
    		linktestProgress = 0;	
		return 0;
	}
    }

    //===========================================
    var response="";
	var oOptions = {
		method: "post",
		asynchronous: true,
		parameters: { action: 'linkTestStart', totalString: dbString},
		onSuccess: function (oXHR, oJson) {
             response = oXHR.responseText;

		},
		onFailure: function (oXHR, oJson) {
			alert("There was an error with the process, Please try again!");
		}
	};
	var req = new Ajax.Request('linktest.php?id='+Math.random(10000,99999), oOptions);
    //return req.responseText;
        $('showbarValue').innerHTML = "In Process";
	 linkgarbageValue=0;	/* Initailizing the variables with */
	 linktestcomplete = 0;		/* zero to start the link test 2nd time again */
         linkTestOnOffButton();
	 linktestfailureTime=0;
	var x = setTimeout('getLinkTestStatus()', 25000);
        //getLinkTestStatus();
   }
}
var y = 0;
var linktempResponse;
function linkTestOnOffButton()
{
    
	//if (tempResponse != 40 && tempResponse !=41 && tempResponse != 5 && tempResponse != undefined && tempResponse != 53 && tempResponse != 50 && garbageValue != 1) {
        if(linktestcomplete != 1){
		if (y == 0) {
			$('linktestonoffimage').innerHTML ="<img src='images/pushButton_On.gif'>";
			y = 1;
		}
		else {
			$('linktestonoffimage').innerHTML = "<img src='images/pushButton_Off.gif'>";
			y = 0;
		}
                //if(linktestfailureTime < 10){
                    //linktestfailureTime++;
                    t1 = setTimeout('linkTestOnOffButton()', 500);
                //}
	}
	else
			$('linktestonoffimage').innerHTML ="<img src='images/pushButton_On.gif'>";
}

function getLinkTestStatus(){
    var response = "";
    var responseStatus = false;
	var oOptions = {
		method: "get",
		asynchronous: true,
		parameters: { action: 'linkTestStatus'},
		onSuccess: function (oXHR, oJson) {
                 response = oXHR.responseText;
                 responseArray = response.split(" ");
	 	 linktempResponse = responseArray[0];
                 switch(linktempResponse){
                     case "0":
                         //responseStatus = true;
                         $('showbarValue').innerHTML = "Failure";
                         linkgarbageValue = 1;
			 linktestcomplete = 1;
                         break;

                     case "3":
                         //responseStatus = true;
                         $('showbarValue').innerHTML = "Success";
                         linkgarbageValue = 1;
			 linktestcomplete = 1;
                         break;
		     case "4":
                         $('showbarValue').innerHTML = "Complete";
                         linkgarbageValue = 1;
			 linktestcomplete = 1;
			 break;
                     case "2":
        		if(linkgarbageValue != 1 && linktestfailureTime >= 120){
		            $('showbarValue').innerHTML = "Timed Out";
  		            linkgarbageValue = 1;
			    linktestcomplete = 1;
			}
                     //   var t1 = setTimeout('getLinkTestStatus()', 500);
                         break;
                 }


		},
		onFailure: function (oXHR, oJson) {
			 linktestcomplete = 1;
			alert("There was an error with the process, Please try again!");
		}
	};
	var req = new Ajax.Request('linktest.php?id='+Math.random(10000,99999), oOptions);
         
        if(linkgarbageValue !=1 && linktestfailureTime < 120){
            linktestfailureTime++;
            var t1 = setTimeout('getLinkTestStatus()', 500);

        }
	/* After getting the link status, make the link test button active */
	if(linktestcomplete){
    		document.getElementById('linktest').disabled = false;
   		linktestProgress = 0;
	}
}

function convertbkgrdScanChanList(){
    var arr = $('scanChanListString0').value.split("");
      for(i=0; i<=12;i++){
        if(arr[i] == 1)
            $('bgChanList0'+i).checked=true;
      }
}

function convertScanChanToString(){
    $('scanChanListString0').value = "";
      for(i=0; i<=12;i++){
        if($('bgChanList0'+i).checked == true)
            $('scanChanListString0').value += '1';
        else
            $('scanChanListString0').value += '0';
      }
}

function resetButton(x,y){
        var radioButtons = document.getElementsByName("rebootAP");
        var radioButtons1 = document.getElementsByName("resetConfiguration");
        if(radioButtons[0].value==1 && x==1)
                radioButtons1[1].checked=true;
        else if(radioButtons1[0].value==1 && y==1)
                radioButtons[1].checked=true;
}

var cStatus;
function cloudSta(){
//alert("hello");
	var oOptions = {
            method: "get",
            asynchronous: false,
			parameters: { action: 'activationState'},
            onSuccess: function (oXHR, oJson) {
                var response = oXHR.responseText;
				//alert("response ="+response);
				//alert("status = "+cStatus);
				if((cStatus!=response && cStatus!=undefined) || (response=='' && cStatus!='' && cStatus!=undefined)){
				cStatus=response;
				document.location.reload(true);
				//window.parent.location.reload();
				
				}
				cStatus=response;
				},
            onFailure: function (oXHR, oJson) {
                alert("There was an error with the process, Please try again!");
            }
        };

	var req = new Ajax.Request('cloud.php?id='+Math.random(10000,99999), oOptions);
	var t = setTimeout("cloudSta()", 5000);
}
var uStatus;
function cloudUI(){
var oOptions = {
            method: "get",
            asynchronous: false,
			parameters: { action: 'stanaloneUIState'},
            onSuccess: function (oXHR, oJson) {
                var response = oXHR.responseText;
				if(uStatus!=response && uStatus!=undefined){
				ustatus=response;
				//document.location.reload(true);
				window.parent.location.reload();
				}
				uStatus=response;
				},
            onFailure: function (oXHR, oJson) {
                alert("There was an error with the process, Please try again!");
            }
        };

	var req = new Ajax.Request('cloud.php?id='+Math.random(10000,99999), oOptions);
	var t = setTimeout("cloudUI()", 5500);
}
var conStatus;
function cloudConnection(){
var oOptions = {
            method: "get",
            asynchronous: false,
			parameters: { action: 'connectionState'},
            onSuccess: function (oXHR, oJson) {
                var response = oXHR.responseText;
				//alert("response = "+response);
				if(conStatus!=response && conStatus!=undefined || (response=='' && conStatus!='' && conStatus!=undefined)){
				conStatus=response;
				//document.location.reload(true);
				window.parent.location.reload();
				}
				conStatus=response;
				},
            onFailure: function (oXHR, oJson) {
                alert("There was an error with the process, Please try again!");
            }
        };

	var req = new Ajax.Request('cloud.php?id='+Math.random(10000,99999), oOptions);
	var t = setTimeout("cloudConnection()", 5500);

}
