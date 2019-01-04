<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
define("EW_DEFAULT_LOCALE", "en_ID", TRUE);
@setlocale(LC_ALL, EW_DEFAULT_LOCALE);
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "posinfo.php" ?>
<?php include_once "employeeinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$pos_view = NULL; // Initialize page object first

class cpos_view extends cpos {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{2F69BB04-5F5E-4632-8EA7-14115FA503E7}";

	// Table name
	var $TableName = 'pos';

	// Page object name
	var $PageObjName = 'pos_view';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			$html .= "<p class=\"ewMessage\">" . $sMessage . "</p>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			$html .= "<table class=\"ewMessageTable\"><tr><td class=\"ewWarningIcon\"></td><td class=\"ewWarningMessage\">" . $sWarningMessage . "</td></tr></table>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			$html .= "<table class=\"ewMessageTable\"><tr><td class=\"ewSuccessIcon\"></td><td class=\"ewSuccessMessage\">" . $sSuccessMessage . "</td></tr></table>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			$html .= "<table class=\"ewMessageTable\"><tr><td class=\"ewErrorIcon\"></td><td class=\"ewErrorMessage\">" . $sErrorMessage . "</td></tr></table>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p class=\"phpmaker\">" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Fotoer exists, display
			echo "<p class=\"phpmaker\">" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language, $UserAgent;

		// User agent
		$UserAgent = ew_UserAgent();
		$GLOBALS["Page"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (pos)
		if (!isset($GLOBALS["pos"])) {
			$GLOBALS["pos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["pos"];
		}
		$KeyUrl = "";
		if (@$_GET["ID"] <> "") {
			$this->RecKey["ID"] = $_GET["ID"];
			$KeyUrl .= "&ID=" . urlencode($this->RecKey["ID"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (employee)
		if (!isset($GLOBALS['employee'])) $GLOBALS['employee'] = new cemployee();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'pos', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "span";
		$this->ExportOptions->TagClassName = "ewExportOption";
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// User profile
		$UserProfile = new cUserProfile();
		$UserProfile->LoadProfile(@$_SESSION[EW_SESSION_USER_PROFILE]);

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate("login.php");
		}
		$Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		$Security->TablePermission_Loaded();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate("login.php");
		}
		if (!$Security->CanView()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("poslist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		if ($Security->IsLoggedIn() && strval($Security->CurrentUserID()) == "") {
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("poslist.php");
		}

		// Update last accessed time
		if ($UserProfile->IsValidUser(session_id())) {
			if (!$Security->IsSysAdmin())
				$UserProfile->SaveProfileToDatabase(CurrentUserName()); // Update last accessed time to user profile
		} else {
			echo $Language->Phrase("UserProfileCorrupted");
		}

		// Get export parameters
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExport = $this->Export; // Get export parameter, used in header
		$gsExportFile = $this->TableVar; // Get export file, used in header
		if (@$_GET["ID"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["ID"]);
		}

		// Setup export options
		$this->SetupExportOptions();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"];
		$this->ID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();
		$this->Page_Redirecting($url);

		 // Close connection
		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $ExportOptions; // Export options
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["ID"] <> "") {
				$this->ID->setQueryStringValue($_GET["ID"]);
				$this->RecKey["ID"] = $this->ID->QueryStringValue;
			} else {
				$bLoadCurrentRecord = TRUE;
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					$this->StartRec = 1; // Initialize start position
					if ($this->Recordset = $this->LoadRecordset()) // Load records
						$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
					if ($this->TotalRecs <= 0) { // No record found
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$this->Page_Terminate("poslist.php"); // Return to list page
					} elseif ($bLoadCurrentRecord) { // Load current record position
						$this->SetUpStartRec(); // Set up start record position

						// Point to current record
						if (intval($this->StartRec) <= intval($this->TotalRecs)) {
							$bMatchRecord = TRUE;
							$this->Recordset->Move($this->StartRec-1);
						}
					} else { // Match key values
						while (!$this->Recordset->EOF) {
							if (strval($this->ID->CurrentValue) == strval($this->Recordset->fields('ID'))) {
								$this->setStartRecordNumber($this->StartRec); // Save record position
								$bMatchRecord = TRUE;
								break;
							} else {
								$this->StartRec++;
								$this->Recordset->MoveNext();
							}
						}
					}
					if (!$bMatchRecord) {
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "poslist.php"; // No matching record, return to list
					} else {
						$this->LoadRowValues($this->Recordset); // Load row values
					}
			}

			// Export data only
			if (in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
				$this->ExportData();
				if ($this->Export == "email")
					$this->Page_Terminate($this->ExportReturnUrl());
				else
					$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "poslist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Call Recordset Selecting event
		$this->Recordset_Selecting($this->CurrentFilter);

		// Load List page SQL
		$sSql = $this->SelectSQL();
		if ($offset > -1 && $rowcnt > -1)
			$sSql .= " LIMIT $rowcnt OFFSET $offset";

		// Load recordset
		$rs = ew_LoadRecordset($sSql);

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		global $conn;
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->ID->setDbValue($rs->fields('ID'));
		$this->IDKirim->setDbValue($rs->fields('IDKirim'));
		$this->Nama->setDbValue($rs->fields('Nama'));
		$this->Alamat1->setDbValue($rs->fields('Alamat1'));
		$this->Alamat2->setDbValue($rs->fields('Alamat2'));
		$this->Alamat3->setDbValue($rs->fields('Alamat3'));
		$this->Alamat4->setDbValue($rs->fields('Alamat4'));
		$this->Kota->setDbValue($rs->fields('Kota'));
		$this->Zip->setDbValue($rs->fields('Zip'));
		$this->Departemen->setDbValue($rs->fields('Departemen'));
		$this->Pengirim->setDbValue($rs->fields('Pengirim'));
		$this->TglKirim->setDbValue($rs->fields('TglKirim'));
		$this->JnsDokumen->setDbValue($rs->fields('JnsDokumen'));
		$this->NoDokumen->setDbValue($rs->fields('NoDokumen'));
		$this->Segera->setDbValue($rs->fields('Segera'));
		$this->InputBy->setDbValue($rs->fields('InputBy'));
		$this->InputTime->setDbValue($rs->fields('InputTime'));
		$this->UpdateBy->setDbValue($rs->fields('UpdateBy'));
		$this->UpdateTime->setDbValue($rs->fields('UpdateTime'));
		$this->del->setDbValue($rs->fields('del'));
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// ID
		// IDKirim
		// Nama
		// Alamat1
		// Alamat2
		// Alamat3
		// Alamat4
		// Kota
		// Zip
		// Departemen
		// Pengirim
		// TglKirim
		// JnsDokumen
		// NoDokumen
		// Segera
		// InputBy
		// InputTime
		// UpdateBy
		// UpdateTime
		// del

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// ID
			$this->ID->ViewValue = $this->ID->CurrentValue;
			$this->ID->ViewCustomAttributes = "";

			// IDKirim
			$this->IDKirim->ViewValue = $this->IDKirim->CurrentValue;
			$this->IDKirim->CssStyle = "font-weight: bold;";
			$this->IDKirim->ViewCustomAttributes = "";

			// Nama
			$this->Nama->ViewValue = $this->Nama->CurrentValue;
			$this->Nama->ViewCustomAttributes = "";

			// Alamat1
			$this->Alamat1->ViewValue = $this->Alamat1->CurrentValue;
			$this->Alamat1->ViewCustomAttributes = "";

			// Alamat2
			$this->Alamat2->ViewValue = $this->Alamat2->CurrentValue;
			$this->Alamat2->ViewCustomAttributes = "";

			// Alamat3
			$this->Alamat3->ViewValue = $this->Alamat3->CurrentValue;
			$this->Alamat3->ViewCustomAttributes = "";

			// Alamat4
			$this->Alamat4->ViewValue = $this->Alamat4->CurrentValue;
			$this->Alamat4->ViewCustomAttributes = "";

			// Kota
			$this->Kota->ViewValue = $this->Kota->CurrentValue;
			$this->Kota->ViewCustomAttributes = "";

			// Zip
			$this->Zip->ViewValue = $this->Zip->CurrentValue;
			$this->Zip->ViewCustomAttributes = "";

			// Departemen
			if (strval($this->Departemen->CurrentValue) <> "") {
				$sFilterWrk = "`IdPos`" . ew_SearchString("=", $this->Departemen->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT `IdPos`, `Department` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `department`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->Departemen->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->Departemen->ViewValue = $this->Departemen->CurrentValue;
				}
			} else {
				$this->Departemen->ViewValue = NULL;
			}
			$this->Departemen->ViewCustomAttributes = "";

			// Pengirim
			$this->Pengirim->ViewValue = $this->Pengirim->CurrentValue;
			$this->Pengirim->ViewCustomAttributes = "";

			// TglKirim
			$this->TglKirim->ViewValue = $this->TglKirim->CurrentValue;
			$this->TglKirim->ViewCustomAttributes = "";

			// JnsDokumen
			if (strval($this->JnsDokumen->CurrentValue) <> "") {
				$sFilterWrk = "`JnsDokumen`" . ew_SearchString("=", $this->JnsDokumen->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT `JnsDokumen`, `JnsDokumen` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pos_jnsdok`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->JnsDokumen->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->JnsDokumen->ViewValue = $this->JnsDokumen->CurrentValue;
				}
			} else {
				$this->JnsDokumen->ViewValue = NULL;
			}
			$this->JnsDokumen->ViewCustomAttributes = "";

			// NoDokumen
			$this->NoDokumen->ViewValue = $this->NoDokumen->CurrentValue;
			$this->NoDokumen->ViewCustomAttributes = "";

			// Segera
			if (strval($this->Segera->CurrentValue) <> "") {
				$this->Segera->ViewValue = "";
				$arwrk = explode(",", strval($this->Segera->CurrentValue));
				$cnt = count($arwrk);
				for ($ari = 0; $ari < $cnt; $ari++) {
					switch (trim($arwrk[$ari])) {
						case $this->Segera->FldTagValue(1):
							$this->Segera->ViewValue .= $this->Segera->FldTagCaption(1) <> "" ? $this->Segera->FldTagCaption(1) : trim($arwrk[$ari]);
							break;
						default:
							$this->Segera->ViewValue .= trim($arwrk[$ari]);
					}
					if ($ari < $cnt-1) $this->Segera->ViewValue .= ew_ViewOptionSeparator($ari);
				}
			} else {
				$this->Segera->ViewValue = NULL;
			}
			$this->Segera->ViewCustomAttributes = "";

			// InputBy
			$this->InputBy->ViewValue = $this->InputBy->CurrentValue;
			$this->InputBy->ViewCustomAttributes = "";

			// InputTime
			$this->InputTime->ViewValue = $this->InputTime->CurrentValue;
			$this->InputTime->ViewCustomAttributes = "";

			// UpdateBy
			$this->UpdateBy->ViewValue = $this->UpdateBy->CurrentValue;
			$this->UpdateBy->ViewCustomAttributes = "";

			// UpdateTime
			$this->UpdateTime->ViewValue = $this->UpdateTime->CurrentValue;
			$this->UpdateTime->ViewCustomAttributes = "";

			// del
			if (strval($this->del->CurrentValue) <> "") {
				$this->del->ViewValue = "";
				$arwrk = explode(",", strval($this->del->CurrentValue));
				$cnt = count($arwrk);
				for ($ari = 0; $ari < $cnt; $ari++) {
					switch (trim($arwrk[$ari])) {
						case $this->del->FldTagValue(1):
							$this->del->ViewValue .= $this->del->FldTagCaption(1) <> "" ? $this->del->FldTagCaption(1) : trim($arwrk[$ari]);
							break;
						default:
							$this->del->ViewValue .= trim($arwrk[$ari]);
					}
					if ($ari < $cnt-1) $this->del->ViewValue .= ew_ViewOptionSeparator($ari);
				}
			} else {
				$this->del->ViewValue = NULL;
			}
			$this->del->ViewCustomAttributes = "";

			// ID
			$this->ID->LinkCustomAttributes = "";
			$this->ID->HrefValue = "";
			$this->ID->TooltipValue = "";

			// IDKirim
			$this->IDKirim->LinkCustomAttributes = "";
			$this->IDKirim->HrefValue = "";
			$this->IDKirim->TooltipValue = "";

			// Nama
			$this->Nama->LinkCustomAttributes = "";
			$this->Nama->HrefValue = "";
			$this->Nama->TooltipValue = "";

			// Alamat1
			$this->Alamat1->LinkCustomAttributes = "";
			$this->Alamat1->HrefValue = "";
			$this->Alamat1->TooltipValue = "";

			// Alamat2
			$this->Alamat2->LinkCustomAttributes = "";
			$this->Alamat2->HrefValue = "";
			$this->Alamat2->TooltipValue = "";

			// Alamat3
			$this->Alamat3->LinkCustomAttributes = "";
			$this->Alamat3->HrefValue = "";
			$this->Alamat3->TooltipValue = "";

			// Alamat4
			$this->Alamat4->LinkCustomAttributes = "";
			$this->Alamat4->HrefValue = "";
			$this->Alamat4->TooltipValue = "";

			// Kota
			$this->Kota->LinkCustomAttributes = "";
			$this->Kota->HrefValue = "";
			$this->Kota->TooltipValue = "";

			// Zip
			$this->Zip->LinkCustomAttributes = "";
			$this->Zip->HrefValue = "";
			$this->Zip->TooltipValue = "";

			// Departemen
			$this->Departemen->LinkCustomAttributes = "";
			$this->Departemen->HrefValue = "";
			$this->Departemen->TooltipValue = "";

			// Pengirim
			$this->Pengirim->LinkCustomAttributes = "";
			$this->Pengirim->HrefValue = "";
			$this->Pengirim->TooltipValue = "";

			// TglKirim
			$this->TglKirim->LinkCustomAttributes = "";
			$this->TglKirim->HrefValue = "";
			$this->TglKirim->TooltipValue = "";

			// JnsDokumen
			$this->JnsDokumen->LinkCustomAttributes = "";
			$this->JnsDokumen->HrefValue = "";
			$this->JnsDokumen->TooltipValue = "";

			// NoDokumen
			$this->NoDokumen->LinkCustomAttributes = "";
			$this->NoDokumen->HrefValue = "";
			$this->NoDokumen->TooltipValue = "";

			// Segera
			$this->Segera->LinkCustomAttributes = "";
			$this->Segera->HrefValue = "";
			$this->Segera->TooltipValue = "";

			// InputBy
			$this->InputBy->LinkCustomAttributes = "";
			$this->InputBy->HrefValue = "";
			$this->InputBy->TooltipValue = "";

			// InputTime
			$this->InputTime->LinkCustomAttributes = "";
			$this->InputTime->HrefValue = "";
			$this->InputTime->TooltipValue = "";

			// UpdateBy
			$this->UpdateBy->LinkCustomAttributes = "";
			$this->UpdateBy->HrefValue = "";
			$this->UpdateBy->TooltipValue = "";

			// UpdateTime
			$this->UpdateTime->LinkCustomAttributes = "";
			$this->UpdateTime->HrefValue = "";
			$this->UpdateTime->TooltipValue = "";

			// del
			$this->del->LinkCustomAttributes = "";
			$this->del->HrefValue = "";
			$this->del->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\">" . "<img src=\"phpimages/print.gif\" alt=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendly")) . "\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendly")) . "\" width=\"16\" height=\"16\" style=\"border: 0;\">" . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\">" . "<img src=\"phpimages/exportxls.gif\" alt=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcel")) . "\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcel")) . "\" width=\"16\" height=\"16\" style=\"border: 0;\">" . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\">" . "<img src=\"phpimages/exportdoc.gif\" alt=\"" . ew_HtmlEncode($Language->Phrase("ExportToWord")) . "\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWord")) . "\" width=\"16\" height=\"16\" style=\"border: 0;\">" . "</a>";
		$item->Visible = TRUE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\">" . "<img src=\"phpimages/exporthtml.gif\" alt=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtml")) . "\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtml")) . "\" width=\"16\" height=\"16\" style=\"border: 0;\">" . "</a>";
		$item->Visible = FALSE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\">" . "<img src=\"phpimages/exportxml.gif\" alt=\"" . ew_HtmlEncode($Language->Phrase("ExportToXml")) . "\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXml")) . "\" width=\"16\" height=\"16\" style=\"border: 0;\">" . "</a>";
		$item->Visible = FALSE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\">" . "<img src=\"phpimages/exportcsv.gif\" alt=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsv")) . "\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsv")) . "\" width=\"16\" height=\"16\" style=\"border: 0;\">" . "</a>";
		$item->Visible = FALSE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\">" . "<img src=\"phpimages/exportpdf.gif\" alt=\"" . ew_HtmlEncode($Language->Phrase("ExportToPdf")) . "\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPdf")) . "\" width=\"16\" height=\"16\" style=\"border: 0;\">" . "</a>";
		$item->Visible = TRUE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$item->Body = "<a id=\"emf_pos\" href=\"javascript:void(0);\" onclick=\"ew_EmailDialogShow({lnk:'emf_pos',hdr:ewLanguage.Phrase('ExportToEmail'),key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false});\">" . "<img src=\"phpimages/exportemail.gif\" alt=\"" . ew_HtmlEncode($Language->Phrase("ExportToEmail")) . "\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToEmail")) . "\" width=\"16\" height=\"16\" style=\"border: 0;\">" . "</a>";
		$item->Visible = TRUE;

		// Hide options for export/action
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = FALSE;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if ($rs = $this->LoadRecordset())
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;
		$this->SetUpStartRec(); // Set up start record position

		// Set the last record to display
		if ($this->DisplayRecs <= 0) {
			$this->StopRec = $this->TotalRecs;
		} else {
			$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
		}
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$ExportDoc = ew_ExportDocument($this, "v");
		$ParentTable = "";
		if ($bSelectLimit) {
			$StartRec = 1;
			$StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {
			$StartRec = $this->StartRec;
			$StopRec = $this->StopRec;
		}
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$ExportDoc->Text .= $sHeader;
		$this->ExportDocument($ExportDoc, $rs, $StartRec, $StopRec, "view");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$ExportDoc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Export header and footer
		$ExportDoc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED)
			echo ew_DebugMsg();

		// Output data
		if ($this->Export == "email") {
			$this->ExportEmail($ExportDoc->Text);
		} else {
			$ExportDoc->Export();
		}
	}

	// Export email
	function ExportEmail($EmailContent) {
		global $gTmpImages, $Language;
		$sSender = @$_GET["sender"];
		$sRecipient = @$_GET["recipient"];
		$sCc = @$_GET["cc"];
		$sBcc = @$_GET["bcc"];
		$sContentType = @$_GET["contenttype"];

		// Subject
		$sSubject = ew_StripSlashes(@$_GET["subject"]);
		$sEmailSubject = $sSubject;

		// Message
		$sContent = ew_StripSlashes(@$_GET["message"]);
		$sEmailMessage = $sContent;

		// Check sender
		if ($sSender == "") {
			$this->setFailureMessage($Language->Phrase("EnterSenderEmail"));
			return;
		}
		if (!ew_CheckEmail($sSender)) {
			$this->setFailureMessage($Language->Phrase("EnterProperSenderEmail"));
			return;
		}

		// Check recipient
		if (!ew_CheckEmailList($sRecipient, EW_MAX_EMAIL_RECIPIENT)) {
			$this->setFailureMessage($Language->Phrase("EnterProperRecipientEmail"));
			return;
		}

		// Check cc
		if (!ew_CheckEmailList($sCc, EW_MAX_EMAIL_RECIPIENT)) {
			$this->setFailureMessage($Language->Phrase("EnterProperCcEmail"));
			return;
		}

		// Check bcc
		if (!ew_CheckEmailList($sBcc, EW_MAX_EMAIL_RECIPIENT)) {
			$this->setFailureMessage($Language->Phrase("EnterProperBccEmail"));
			return;
		}

		// Check email sent count
		if (!isset($_SESSION[EW_EXPORT_EMAIL_COUNTER]))
			$_SESSION[EW_EXPORT_EMAIL_COUNTER] = 0;
		if (intval($_SESSION[EW_EXPORT_EMAIL_COUNTER]) > EW_MAX_EMAIL_SENT_COUNT) {
			$this->setFailureMessage($Language->Phrase("ExceedMaxEmailExport"));
			return;
		}

		// Send email
		$Email = new cEmail();
		$Email->Sender = $sSender; // Sender
		$Email->Recipient = $sRecipient; // Recipient
		$Email->Cc = $sCc; // Cc
		$Email->Bcc = $sBcc; // Bcc
		$Email->Subject = $sEmailSubject; // Subject
		$Email->Format = ($sContentType == "url") ? "text" : "html";
		$Email->Charset = EW_EMAIL_CHARSET;
		if ($sEmailMessage <> "") {
			$sEmailMessage = ew_RemoveXSS($sEmailMessage);
			$sEmailMessage .= ($sContentType == "url") ? "\r\n\r\n" : "<br><br>";
		}
		if ($sContentType == "url") {
			$sUrl = ew_ConvertFullUrl(ew_CurrentPage() . "?" . $this->ExportQueryString());
			$sEmailMessage .= $sUrl; // send URL only
		} else {
			foreach ($gTmpImages as $tmpimage)
				$Email->AddEmbeddedImage($tmpimage);
			$sEmailMessage .= $EmailContent; // send HTML
		}
		$Email->Content = $sEmailMessage; // Content
		$EventArgs = array();
		$bEmailSent = FALSE;
		if ($this->Email_Sending($Email, $EventArgs))
			$bEmailSent = $Email->Send();

		// Check email sent status
		if ($bEmailSent) {

			// Update email sent count
			$_SESSION[EW_EXPORT_EMAIL_COUNTER]++;

			// Sent email success
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("SendEmailSuccess")); // Set up success message
		} else {

			// Sent email failure
			$this->setFailureMessage($Email->SendErrDescription);
		}
	}

	// Export QueryString
	function ExportQueryString() {

		// Initialize
		$sQry = "export=html";

		// Add record key QueryString
		$sQry .= "&" . substr($this->KeyUrl("", ""), 1);
		return $sQry;
	}

	// Show link optionally based on User ID
	function ShowOptionLink($id = "") {
		global $Security;
		if ($Security->IsLoggedIn() && !$Security->IsAdmin() && !$this->UserIDAllow($id))
			return $Security->IsValidUserID($this->InputBy->CurrentValue);
		return TRUE;
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
		// PRINT rip140414

		$item = &$this->ExportOptions->Add("print");
		$item->Body = '<a href="cetakpos.php?op=barcode&ID='.$_REQUEST['ID'].'" target="_blank"><img src="phpimages/print.gif"> <B>PRINT BARCODE POS SURAT</B></a>';
		$item->Visible = TRUE;   
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(TRUE) ?>
<?php

// Create page object
if (!isset($pos_view)) $pos_view = new cpos_view();

// Page init
$pos_view->Page_Init();

// Page main
$pos_view->Page_Main();
?>
<?php include_once "header.php" ?>
<?php if ($pos->Export == "") { ?>
<script type="text/javascript">

// Page object
var pos_view = new ew_Page("pos_view");
pos_view.PageID = "view"; // Page ID
var EW_PAGE_ID = pos_view.PageID; // For backward compatibility

// Form object
var fposview = new ew_Form("fposview");

// Form_CustomValidate event
fposview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fposview.ValidateRequired = true;
<?php } else { ?>
fposview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fposview.Lists["x_Departemen"] = {"LinkField":"x_IdPos","Ajax":null,"AutoFill":false,"DisplayFields":["x_Department","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fposview.Lists["x_JnsDokumen"] = {"LinkField":"x_JnsDokumen","Ajax":true,"AutoFill":false,"DisplayFields":["x_JnsDokumen","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<div id="ewDetailsDiv" style="visibility: hidden; z-index: 11000;"></div>
<script type="text/javascript">

// Details preview
var ewDetailsDiv, ewDetailsTimer = null;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<p><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("View") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $pos->TableCaption() ?>&nbsp;&nbsp;</span><?php $pos_view->ExportOptions->Render("body"); ?>
</p>
<?php if ($pos->Export == "") { ?>
<p class="phpmaker">
<a href="<?php echo $pos_view->ListUrl ?>" id="a_BackToList" class="ewLink"><?php echo $Language->Phrase("BackToList") ?></a>&nbsp;
<?php if ($Security->CanAdd()) { ?>
<?php if ($pos_view->AddUrl <> "") { ?>
<a href="<?php echo $pos_view->AddUrl ?>" id="a_AddLink" class="ewLink"><?php echo $Language->Phrase("ViewPageAddLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
<?php if ($Security->CanEdit()) { ?>
<?php if ($pos_view->ShowOptionLink('edit')) { ?>
<?php if ($pos_view->EditUrl <> "") { ?>
<a href="<?php echo $pos_view->EditUrl ?>" id="a_EditLink" class="ewLink"><?php echo $Language->Phrase("ViewPageEditLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
<?php } ?>
<?php if ($Security->CanAdd()) { ?>
<?php if ($pos_view->ShowOptionLink('add')) { ?>
<?php if ($pos_view->CopyUrl <> "") { ?>
<a href="<?php echo $pos_view->CopyUrl ?>" id="a_CopyLink" class="ewLink"><?php echo $Language->Phrase("ViewPageCopyLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
<?php } ?>
</p>
<?php } ?>
<?php $pos_view->ShowPageHeader(); ?>
<?php
$pos_view->ShowMessage();
?>
<?php if ($pos->Export == "") { ?>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager"><tr><td>
<?php if (!isset($pos_view->Pager)) $pos_view->Pager = new cPrevNextPager($pos_view->StartRec, $pos_view->DisplayRecs, $pos_view->TotalRecs) ?>
<?php if ($pos_view->Pager->RecordCount > 0) { ?>
	<table cellspacing="0" class="ewStdTable"><tbody><tr><td><span class="phpmaker"><?php echo $Language->Phrase("Page") ?>&nbsp;</span></td>
<!--first page button-->
	<?php if ($pos_view->Pager->FirstButton->Enabled) { ?>
	<td><a href="<?php echo $pos_view->PageUrl() ?>start=<?php echo $pos_view->Pager->FirstButton->Start ?>"><img src="phpimages/first.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" style="border: 0;"></a></td>
	<?php } else { ?>
	<td><img src="phpimages/firstdisab.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" style="border: 0;"></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($pos_view->Pager->PrevButton->Enabled) { ?>
	<td><a href="<?php echo $pos_view->PageUrl() ?>start=<?php echo $pos_view->Pager->PrevButton->Start ?>"><img src="phpimages/prev.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" style="border: 0;"></a></td>
	<?php } else { ?>
	<td><img src="phpimages/prevdisab.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" style="border: 0;"></td>
	<?php } ?>
<!--current page number-->
	<td><input type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" id="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $pos_view->Pager->CurrentPage ?>" size="4"></td>
<!--next page button-->
	<?php if ($pos_view->Pager->NextButton->Enabled) { ?>
	<td><a href="<?php echo $pos_view->PageUrl() ?>start=<?php echo $pos_view->Pager->NextButton->Start ?>"><img src="phpimages/next.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" style="border: 0;"></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/nextdisab.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" style="border: 0;"></td>
	<?php } ?>
<!--last page button-->
	<?php if ($pos_view->Pager->LastButton->Enabled) { ?>
	<td><a href="<?php echo $pos_view->PageUrl() ?>start=<?php echo $pos_view->Pager->LastButton->Start ?>"><img src="phpimages/last.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" style="border: 0;"></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/lastdisab.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" style="border: 0;"></td>
	<?php } ?>
	<td><span class="phpmaker">&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $pos_view->Pager->PageCount ?></span></td>
	</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($pos_view->SearchWhere == "0=101") { ?>
	<span class="phpmaker"><?php echo $Language->Phrase("EnterSearchCriteria") ?></span>
	<?php } else { ?>
	<span class="phpmaker"><?php echo $Language->Phrase("NoRecord") ?></span>
	<?php } ?>
	<?php } else { ?>
	<span class="phpmaker"><?php echo $Language->Phrase("NoPermission") ?></span>
	<?php } ?>
<?php } ?>
	</td>
</tr></table>
</form>
<br>
<?php } ?>
<form name="fposview" id="fposview" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="pos">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_posview" class="ewTable">
<?php if ($pos->ID->Visible) { // ID ?>
	<tr id="r_ID"<?php echo $pos->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_pos_ID"><table class="ewTableHeaderBtn"><tr><td><?php echo $pos->ID->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $pos->ID->CellAttributes() ?>><span id="el_pos_ID">
<span<?php echo $pos->ID->ViewAttributes() ?>>
<?php echo $pos->ID->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($pos->IDKirim->Visible) { // IDKirim ?>
	<tr id="r_IDKirim"<?php echo $pos->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_pos_IDKirim"><table class="ewTableHeaderBtn"><tr><td><?php echo $pos->IDKirim->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $pos->IDKirim->CellAttributes() ?>><span id="el_pos_IDKirim">
<span<?php echo $pos->IDKirim->ViewAttributes() ?>>
<?php echo $pos->IDKirim->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($pos->Nama->Visible) { // Nama ?>
	<tr id="r_Nama"<?php echo $pos->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_pos_Nama"><table class="ewTableHeaderBtn"><tr><td><?php echo $pos->Nama->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $pos->Nama->CellAttributes() ?>><span id="el_pos_Nama">
<span<?php echo $pos->Nama->ViewAttributes() ?>>
<?php echo $pos->Nama->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($pos->Alamat1->Visible) { // Alamat1 ?>
	<tr id="r_Alamat1"<?php echo $pos->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_pos_Alamat1"><table class="ewTableHeaderBtn"><tr><td><?php echo $pos->Alamat1->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $pos->Alamat1->CellAttributes() ?>><span id="el_pos_Alamat1">
<span<?php echo $pos->Alamat1->ViewAttributes() ?>>
<?php echo $pos->Alamat1->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($pos->Alamat2->Visible) { // Alamat2 ?>
	<tr id="r_Alamat2"<?php echo $pos->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_pos_Alamat2"><table class="ewTableHeaderBtn"><tr><td><?php echo $pos->Alamat2->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $pos->Alamat2->CellAttributes() ?>><span id="el_pos_Alamat2">
<span<?php echo $pos->Alamat2->ViewAttributes() ?>>
<?php echo $pos->Alamat2->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($pos->Alamat3->Visible) { // Alamat3 ?>
	<tr id="r_Alamat3"<?php echo $pos->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_pos_Alamat3"><table class="ewTableHeaderBtn"><tr><td><?php echo $pos->Alamat3->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $pos->Alamat3->CellAttributes() ?>><span id="el_pos_Alamat3">
<span<?php echo $pos->Alamat3->ViewAttributes() ?>>
<?php echo $pos->Alamat3->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($pos->Alamat4->Visible) { // Alamat4 ?>
	<tr id="r_Alamat4"<?php echo $pos->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_pos_Alamat4"><table class="ewTableHeaderBtn"><tr><td><?php echo $pos->Alamat4->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $pos->Alamat4->CellAttributes() ?>><span id="el_pos_Alamat4">
<span<?php echo $pos->Alamat4->ViewAttributes() ?>>
<?php echo $pos->Alamat4->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($pos->Kota->Visible) { // Kota ?>
	<tr id="r_Kota"<?php echo $pos->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_pos_Kota"><table class="ewTableHeaderBtn"><tr><td><?php echo $pos->Kota->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $pos->Kota->CellAttributes() ?>><span id="el_pos_Kota">
<span<?php echo $pos->Kota->ViewAttributes() ?>>
<?php echo $pos->Kota->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($pos->Zip->Visible) { // Zip ?>
	<tr id="r_Zip"<?php echo $pos->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_pos_Zip"><table class="ewTableHeaderBtn"><tr><td><?php echo $pos->Zip->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $pos->Zip->CellAttributes() ?>><span id="el_pos_Zip">
<span<?php echo $pos->Zip->ViewAttributes() ?>>
<?php echo $pos->Zip->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($pos->Departemen->Visible) { // Departemen ?>
	<tr id="r_Departemen"<?php echo $pos->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_pos_Departemen"><table class="ewTableHeaderBtn"><tr><td><?php echo $pos->Departemen->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $pos->Departemen->CellAttributes() ?>><span id="el_pos_Departemen">
<span<?php echo $pos->Departemen->ViewAttributes() ?>>
<?php echo $pos->Departemen->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($pos->Pengirim->Visible) { // Pengirim ?>
	<tr id="r_Pengirim"<?php echo $pos->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_pos_Pengirim"><table class="ewTableHeaderBtn"><tr><td><?php echo $pos->Pengirim->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $pos->Pengirim->CellAttributes() ?>><span id="el_pos_Pengirim">
<span<?php echo $pos->Pengirim->ViewAttributes() ?>>
<?php echo $pos->Pengirim->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($pos->TglKirim->Visible) { // TglKirim ?>
	<tr id="r_TglKirim"<?php echo $pos->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_pos_TglKirim"><table class="ewTableHeaderBtn"><tr><td><?php echo $pos->TglKirim->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $pos->TglKirim->CellAttributes() ?>><span id="el_pos_TglKirim">
<span<?php echo $pos->TglKirim->ViewAttributes() ?>>
<?php echo $pos->TglKirim->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($pos->JnsDokumen->Visible) { // JnsDokumen ?>
	<tr id="r_JnsDokumen"<?php echo $pos->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_pos_JnsDokumen"><table class="ewTableHeaderBtn"><tr><td><?php echo $pos->JnsDokumen->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $pos->JnsDokumen->CellAttributes() ?>><span id="el_pos_JnsDokumen">
<span<?php echo $pos->JnsDokumen->ViewAttributes() ?>>
<?php echo $pos->JnsDokumen->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($pos->NoDokumen->Visible) { // NoDokumen ?>
	<tr id="r_NoDokumen"<?php echo $pos->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_pos_NoDokumen"><table class="ewTableHeaderBtn"><tr><td><?php echo $pos->NoDokumen->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $pos->NoDokumen->CellAttributes() ?>><span id="el_pos_NoDokumen">
<span<?php echo $pos->NoDokumen->ViewAttributes() ?>>
<?php echo $pos->NoDokumen->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($pos->Segera->Visible) { // Segera ?>
	<tr id="r_Segera"<?php echo $pos->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_pos_Segera"><table class="ewTableHeaderBtn"><tr><td><?php echo $pos->Segera->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $pos->Segera->CellAttributes() ?>><span id="el_pos_Segera">
<span<?php echo $pos->Segera->ViewAttributes() ?>>
<?php echo $pos->Segera->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($pos->InputBy->Visible) { // InputBy ?>
	<tr id="r_InputBy"<?php echo $pos->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_pos_InputBy"><table class="ewTableHeaderBtn"><tr><td><?php echo $pos->InputBy->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $pos->InputBy->CellAttributes() ?>><span id="el_pos_InputBy">
<span<?php echo $pos->InputBy->ViewAttributes() ?>>
<?php echo $pos->InputBy->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($pos->InputTime->Visible) { // InputTime ?>
	<tr id="r_InputTime"<?php echo $pos->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_pos_InputTime"><table class="ewTableHeaderBtn"><tr><td><?php echo $pos->InputTime->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $pos->InputTime->CellAttributes() ?>><span id="el_pos_InputTime">
<span<?php echo $pos->InputTime->ViewAttributes() ?>>
<?php echo $pos->InputTime->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($pos->UpdateBy->Visible) { // UpdateBy ?>
	<tr id="r_UpdateBy"<?php echo $pos->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_pos_UpdateBy"><table class="ewTableHeaderBtn"><tr><td><?php echo $pos->UpdateBy->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $pos->UpdateBy->CellAttributes() ?>><span id="el_pos_UpdateBy">
<span<?php echo $pos->UpdateBy->ViewAttributes() ?>>
<?php echo $pos->UpdateBy->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($pos->UpdateTime->Visible) { // UpdateTime ?>
	<tr id="r_UpdateTime"<?php echo $pos->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_pos_UpdateTime"><table class="ewTableHeaderBtn"><tr><td><?php echo $pos->UpdateTime->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $pos->UpdateTime->CellAttributes() ?>><span id="el_pos_UpdateTime">
<span<?php echo $pos->UpdateTime->ViewAttributes() ?>>
<?php echo $pos->UpdateTime->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($pos->del->Visible) { // del ?>
	<tr id="r_del"<?php echo $pos->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_pos_del"><table class="ewTableHeaderBtn"><tr><td><?php echo $pos->del->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $pos->del->CellAttributes() ?>><span id="el_pos_del">
<span<?php echo $pos->del->ViewAttributes() ?>>
<?php echo $pos->del->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
</form>
<?php if ($pos->Export == "") { ?>
<br>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager"><tr><td>
<?php if (!isset($pos_view->Pager)) $pos_view->Pager = new cPrevNextPager($pos_view->StartRec, $pos_view->DisplayRecs, $pos_view->TotalRecs) ?>
<?php if ($pos_view->Pager->RecordCount > 0) { ?>
	<table cellspacing="0" class="ewStdTable"><tbody><tr><td><span class="phpmaker"><?php echo $Language->Phrase("Page") ?>&nbsp;</span></td>
<!--first page button-->
	<?php if ($pos_view->Pager->FirstButton->Enabled) { ?>
	<td><a href="<?php echo $pos_view->PageUrl() ?>start=<?php echo $pos_view->Pager->FirstButton->Start ?>"><img src="phpimages/first.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" style="border: 0;"></a></td>
	<?php } else { ?>
	<td><img src="phpimages/firstdisab.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" style="border: 0;"></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($pos_view->Pager->PrevButton->Enabled) { ?>
	<td><a href="<?php echo $pos_view->PageUrl() ?>start=<?php echo $pos_view->Pager->PrevButton->Start ?>"><img src="phpimages/prev.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" style="border: 0;"></a></td>
	<?php } else { ?>
	<td><img src="phpimages/prevdisab.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" style="border: 0;"></td>
	<?php } ?>
<!--current page number-->
	<td><input type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" id="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $pos_view->Pager->CurrentPage ?>" size="4"></td>
<!--next page button-->
	<?php if ($pos_view->Pager->NextButton->Enabled) { ?>
	<td><a href="<?php echo $pos_view->PageUrl() ?>start=<?php echo $pos_view->Pager->NextButton->Start ?>"><img src="phpimages/next.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" style="border: 0;"></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/nextdisab.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" style="border: 0;"></td>
	<?php } ?>
<!--last page button-->
	<?php if ($pos_view->Pager->LastButton->Enabled) { ?>
	<td><a href="<?php echo $pos_view->PageUrl() ?>start=<?php echo $pos_view->Pager->LastButton->Start ?>"><img src="phpimages/last.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" style="border: 0;"></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/lastdisab.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" style="border: 0;"></td>
	<?php } ?>
	<td><span class="phpmaker">&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $pos_view->Pager->PageCount ?></span></td>
	</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($pos_view->SearchWhere == "0=101") { ?>
	<span class="phpmaker"><?php echo $Language->Phrase("EnterSearchCriteria") ?></span>
	<?php } else { ?>
	<span class="phpmaker"><?php echo $Language->Phrase("NoRecord") ?></span>
	<?php } ?>
	<?php } else { ?>
	<span class="phpmaker"><?php echo $Language->Phrase("NoPermission") ?></span>
	<?php } ?>
<?php } ?>
	</td>
</tr></table>
</form>
<?php } ?>
<br>
<script type="text/javascript">
fposview.Init();
</script>
<?php
$pos_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($pos->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$pos_view->Page_Terminate();
?>
