
<?php
    $this->assign('CIOM', 'active');

    $outcomes = array(
    '1' => 'recovered/resolved',
    '2' => 'recovering/resolving',
    '3' => 'not recovered/not resolved',
    '4' => 'recovered/resolved with sequelae',
    '5' => 'fatal',
    '6' => 'unknown'
    );
    $actiondrug = array('1' => 'Drug withdrawn',
    '2' => 'Dose reduced',
    '3' => 'Dose increased',
    '4' => 'Dose not changed',
    '5' => 'Unknown',
    '6' => 'Not applicable');

    $serious = array('1' => 'Yes', '2' => 'No');
    $drugcharacterization = array('1' => 'Suspect', '2' => 'Concomitant', '3' => 'Interacting');
    // debug($e2b);

    $drugadministrationroute = ['001' => 'Auricular (otic)', '002' => 'Buccal', '003' => 'Cutaneous', '004' => 'Dental', '005' => 'Endocervical',
'006' => 'Endosinusial', '007' => 'Endotracheal', '008' => 'Epidural', '009' => 'Extra-amniotic', '010' => 'Hemodialysis', '011' => 'Intra corpus cavernosum',
'012' => 'Intra-amniotic', '013' => 'Intra-arterial', '014' => 'Intra-articular', '015' => 'Intra-uterine', '016' => 'Intracardiac', '017' => 'Intracavernous',
'018' => 'Intracerebral', '019' => 'Intracervical', '020' => 'Intracisternal', '021' => 'Intracorneal', '022' => 'Intracoronary', '023' => 'Intradermal',
'024' => 'Intradiscal (intraspinal)', '025' => 'Intrahepatic', '026' => 'Intralesional', '027' => 'Intralymphatic', '028' => 'Intramedullar (bone marrow)', '029' => 'Intrameningeal',
'030' => 'Intramuscular', '031' => 'Intraocular', '032' => 'Intrapericardial', '033' => 'Intraperitoneal', '034' => 'Intrapleural', '035' => 'Intrasynovial',
'036' => 'Intratumor', '037' => 'Intrathecal', '038' => 'Intrathoracic', '039' => 'Intratracheal', '040' => 'Intravenous bolus', '041' => 'Intravenous drip',
'042' => 'Intravenous (not otherwise specified)', '043' => 'Intravesical', '044' => 'Iontophoresis', '045' => 'Nasal',
'046' => 'Occlusive dressing technique', '047' => 'Ophthalmic', '048' => 'Oral', '049' => 'Oropharingeal', '050' => 'Other', '051' => 'Parenteral', '052' => 'Periarticular', '053' => 'Perineural', '054' => 'Rectal',
'055' => 'Respiratory (inhalation)', '056' => 'Retrobulbar', '057' => 'Sunconjunctival', '058' => 'Subcutaneous', '059' => 'Subdermal', '060' => 'Sublingual', '061' => 'Topical', '062' => 'Transdermal',
'063' => 'Transmammary', '064' => 'Transplacental', '065' => 'Unknown', '066' => 'Urethral', '067' => 'Vaginal'];

$time_unit = ['801' => 'Year', '802' => 'Month', '803' => 'Week', '804' => 'Day', '805' => 'Hour', '806' => 'Minute'];

$actions = [
'1'=>'Drug withdrawn',
'2'=>'Dose reduced',
'3'=>'Dose increased',
'4'=>'Dose not changed',
'5'=>'Unknown',
'6'=>'Not applicable'
];

$drugrecurreadministration = ['1' => 'Yes', '2' => 'No', '3' => 'Unknown'];
$reporttype = ['1' => 'Spontaneous', '2' => 'Report from study', '3' => 'Other', '4' => 'Not available to sender (unknown)'];
$qualification = ['1' => 'Physician', '2' => 'Pharmacist', '3' => 'Other Health Professional', '4' => 'Lawyer', '5' => 'Consumer or other non-health professional'];

$isE2bR3 = !empty($isE2bR3);
$ciomExtractionFields = !empty($ciomExtractionFields) ? $ciomExtractionFields : array();

if ($isE2bR3) {
    $reportedInformationRows = array();
    $seenReportedRows = array();
    $reportedMaxRows = 150;
    $reportedSummary = array(
      'Batch Number' => '',
      'Message Identifier' => '',
      'Case Identifier' => '',
      'Message Sender' => '',
      'Message Receiver' => '',
      'Source Organization' => '',
      'Company / MAH' => '',
      'Source City' => '',
      'Source Country' => '',
      'First Received Date' => '',
      'Most Recent Info Date' => '',
      'Message Creation Date' => ''
    );
    $reportedCaseNarrative = '';

    $patientProfile = array(
      'Patient Initials/Name' => '',
      'Sex' => ''
    );
    $patientObservationGroups = array();

    $drugGroups = array();
    $reactionGroups = array();

    $firstNonEmpty = function ($values) {
      foreach ((array) $values as $value) {
        $value = trim((string) $value);
        if ($value !== '') {
          return $value;
        }
      }
      return '';
    };

    $toBooleanState = function ($value) {
      $value = strtolower(trim((string) $value));
      if (in_array($value, array('true', '1', 'yes', 'y'), true)) {
        return true;
      }
      if (in_array($value, array('false', '0', 'no', 'n'), true)) {
        return false;
      }
      return null;
    };

    $booleanText = function ($value) {
      if ($value === true) {
        return 'Yes';
      }
      if ($value === false) {
        return 'No';
      }
      return 'Unknown';
    };

    $formatE2bDate = function ($rawDate) {
      $rawDate = trim((string) $rawDate);
      if ($rawDate === '') {
        return '';
      }
      if (preg_match('/^[0-9]{4}$/', $rawDate)) {
        return $rawDate;
      }
      if (preg_match('/^[0-9]{6}$/', $rawDate)) {
        return substr($rawDate, 0, 4) . '-' . substr($rawDate, 4, 2);
      }
      if (preg_match('/^[0-9]{8}$/', $rawDate)) {
        return substr($rawDate, 0, 4) . '-' . substr($rawDate, 4, 2) . '-' . substr($rawDate, 6, 2);
      }
      return $rawDate;
    };

    $formatE2bTimestamp = function ($rawValue) use ($formatE2bDate) {
      $rawValue = trim((string) $rawValue);
      if ($rawValue === '') {
        return '';
      }
      if (preg_match('/^([0-9]{14})([+-][0-9]{4})$/', $rawValue, $matches)) {
        return substr($matches[1], 0, 4) . '-' .
          substr($matches[1], 4, 2) . '-' .
          substr($matches[1], 6, 2) . ' ' .
          substr($matches[1], 8, 2) . ':' .
          substr($matches[1], 10, 2) . ':' .
          substr($matches[1], 12, 2) . ' ' . $matches[2];
      }
      if (preg_match('/^[0-9]{14}$/', $rawValue)) {
        return substr($rawValue, 0, 4) . '-' .
          substr($rawValue, 4, 2) . '-' .
          substr($rawValue, 6, 2) . ' ' .
          substr($rawValue, 8, 2) . ':' .
          substr($rawValue, 10, 2) . ':' .
          substr($rawValue, 12, 2);
      }
      return $formatE2bDate($rawValue);
    };

    $normalizeLabel = function ($field) {
      $label = trim((string) $field['field_label']);
      $path = trim((string) $field['field_path']);
      $key = trim((string) $field['field_key']);

      if (in_array(strtolower($key), array('value', '@value', '@code', '@extension', 'id', '@displayname'))) {
        $segments = explode('/', trim($path, '/'));
        for ($i = count($segments) - 1; $i >= 0; $i--) {
          $segment = preg_replace('/\[[0-9]+\]/', '', $segments[$i]);
          if ($segment === '' || strpos($segment, '@') === 0) {
            continue;
          }
          $segmentLower = strtolower($segment);
          if (in_array($segmentLower, array('value', 'code', 'id', 'effectivetime', 'low', 'high', 'statuscode', 'extension', 'root'))) {
            continue;
          }
          $segment = preg_replace('/([a-z0-9])([A-Z])/', '$1 $2', $segment);
          $segment = str_replace(array('_', '-'), ' ', $segment);
          $label = ucwords(strtolower(trim($segment)));
          break;
        }
      }

      if ($label === '') {
        $label = 'Field';
      }
      return $label;
    };

    foreach ($ciomExtractionFields as $row) {
      if (empty($row['CiomExtractionField'])) {
        continue;
      }
      $field = $row['CiomExtractionField'];
      $fieldValue = trim((string) $field['field_value']);
      if ($fieldValue === '') {
        continue;
      }

      $fieldPath = strtolower((string) $field['field_path']);
      $fieldKey = strtolower((string) $field['field_key']);
      $isAttribute = (strpos($fieldKey, '@') === 0);

      if ($fieldPath === '/mcci_in200100uv01[1]/id[1]/@extension') {
        $reportedSummary['Batch Number'] = $fieldValue;
      } elseif ($fieldPath === '/mcci_in200100uv01[1]/porr_in049016uv[1]/id[1]/@extension') {
        $reportedSummary['Message Identifier'] = $fieldValue;
      } elseif ($fieldPath === '/mcci_in200100uv01[1]/porr_in049016uv[1]/controlactprocess[1]/subject[1]/investigationevent[1]/id[1]/@extension') {
        $reportedSummary['Case Identifier'] = $fieldValue;
      } elseif ($fieldPath === '/mcci_in200100uv01[1]/porr_in049016uv[1]/sender[1]/device[1]/id[1]/@extension') {
        $reportedSummary['Message Sender'] = $fieldValue;
      } elseif ($fieldPath === '/mcci_in200100uv01[1]/porr_in049016uv[1]/receiver[1]/device[1]/id[1]/@extension') {
        $reportedSummary['Message Receiver'] = $fieldValue;
      } elseif ($fieldPath === '/mcci_in200100uv01[1]/porr_in049016uv[1]/controlactprocess[1]/subject[1]/investigationevent[1]/subjectof1[1]/controlactevent[1]/author[1]/assignedentity[1]/representedorganization[1]/name[1]') {
        $reportedSummary['Source Organization'] = $fieldValue;
      } elseif ($fieldPath === '/mcci_in200100uv01[1]/porr_in049016uv[1]/controlactprocess[1]/subject[1]/investigationevent[1]/subjectof1[1]/controlactevent[1]/author[1]/assignedentity[1]/representedorganization[1]/assignedentity[1]/representedorganization[1]/name[1]') {
        $reportedSummary['Company / MAH'] = $fieldValue;
      } elseif ($fieldPath === '/mcci_in200100uv01[1]/porr_in049016uv[1]/controlactprocess[1]/subject[1]/investigationevent[1]/subjectof1[1]/controlactevent[1]/author[1]/assignedentity[1]/addr[1]/city[1]') {
        $reportedSummary['Source City'] = $fieldValue;
      } elseif ($fieldPath === '/mcci_in200100uv01[1]/porr_in049016uv[1]/controlactprocess[1]/subject[1]/investigationevent[1]/subjectof1[1]/controlactevent[1]/author[1]/assignedentity[1]/assignedperson[1]/aslocatedentity[1]/location[1]/code[1]/@code') {
        $reportedSummary['Source Country'] = $fieldValue;
      } elseif ($fieldPath === '/mcci_in200100uv01[1]/porr_in049016uv[1]/controlactprocess[1]/subject[1]/investigationevent[1]/effectivetime[1]/low[1]/@value') {
        $reportedSummary['First Received Date'] = $fieldValue;
      } elseif ($fieldPath === '/mcci_in200100uv01[1]/porr_in049016uv[1]/controlactprocess[1]/subject[1]/investigationevent[1]/availabilitytime[1]/@value') {
        $reportedSummary['Most Recent Info Date'] = $fieldValue;
      } elseif ($fieldPath === '/mcci_in200100uv01[1]/porr_in049016uv[1]/creationtime[1]/@value') {
        $reportedSummary['Message Creation Date'] = $fieldValue;
      } elseif ($fieldPath === '/mcci_in200100uv01[1]/porr_in049016uv[1]/controlactprocess[1]/subject[1]/investigationevent[1]/text[1]') {
        $reportedCaseNarrative = $fieldValue;
      }

      if ($fieldPath === '/mcci_in200100uv01[1]/porr_in049016uv[1]/controlactprocess[1]/subject[1]/investigationevent[1]/component[1]/adverseeventassessment[1]/subject1[1]/primaryrole[1]/player1[1]/name[1]') {
        $patientProfile['Patient Initials/Name'] = $fieldValue;
      }
      if ($fieldPath === '/mcci_in200100uv01[1]/porr_in049016uv[1]/controlactprocess[1]/subject[1]/investigationevent[1]/component[1]/adverseeventassessment[1]/subject1[1]/primaryrole[1]/player1[1]/administrativegendercode[1]/@code') {
        $patientProfile['Sex'] = $fieldValue;
      }

      $patientObservationMatches = array();
      if (preg_match('#/subjectof2\[1\]/organizer\[1\]/component\[(\d+)\]/observation\[1\]/(.+)$#i', $fieldPath, $patientObservationMatches)) {
        $observationIndex = (int) $patientObservationMatches[1];
        $patientSubPath = strtolower($patientObservationMatches[2]);
        if (!isset($patientObservationGroups[$observationIndex])) {
          $patientObservationGroups[$observationIndex] = array(
            'meddra_code' => '',
            'start_date' => ''
          );
        }
        if ($patientSubPath === 'code[1]/@code') {
          $patientObservationGroups[$observationIndex]['meddra_code'] = $fieldValue;
        } elseif ($patientSubPath === 'effectivetime[1]/low[1]/@value') {
          $patientObservationGroups[$observationIndex]['start_date'] = $fieldValue;
        }
      }

      $reactionPathMatches = array();
      if (preg_match('#^(.*?/subjectof2\[\d+\]/observation\[\d+\])/(.+)$#i', $fieldPath, $reactionPathMatches)) {
        $reactionPath = $reactionPathMatches[1];
        $reactionSubPath = strtolower($reactionPathMatches[2]);
        if (!isset($reactionGroups[$reactionPath])) {
          $reactionGroups[$reactionPath] = array(
            'sequence' => !empty($field['sequence']) ? (int) $field['sequence'] : 999999,
            'event_code' => '',
            'meddra_code' => '',
            'meddra_term' => '',
            'start_date' => '',
            'country_code' => '',
            'outbound' => array()
          );
        } elseif (!empty($field['sequence']) && (int) $field['sequence'] < $reactionGroups[$reactionPath]['sequence']) {
          $reactionGroups[$reactionPath]['sequence'] = (int) $field['sequence'];
        }

        if ($reactionSubPath === 'code[1]/@code') {
          $reactionGroups[$reactionPath]['event_code'] = $fieldValue;
        } elseif ($reactionSubPath === 'value[1]/@code') {
          $reactionGroups[$reactionPath]['meddra_code'] = $fieldValue;
        } elseif ($reactionSubPath === 'value[1]/@displayname') {
          $reactionGroups[$reactionPath]['meddra_term'] = $fieldValue;
        } elseif ($reactionSubPath === 'effectivetime[1]/low[1]/@value') {
          $reactionGroups[$reactionPath]['start_date'] = $fieldValue;
        } elseif ($reactionSubPath === 'location[1]/locatedentity[1]/locatedplace[1]/code[1]/@code') {
          $reactionGroups[$reactionPath]['country_code'] = $fieldValue;
        } else {
          $outboundPathMatches = array();
          if (preg_match('#^outboundrelationship2\[(\d+)\]/observation\[1\]/(.+)$#i', $reactionSubPath, $outboundPathMatches)) {
            $outboundIndex = (int) $outboundPathMatches[1];
            $outboundSubPath = strtolower($outboundPathMatches[2]);
            if (!isset($reactionGroups[$reactionPath]['outbound'][$outboundIndex])) {
              $reactionGroups[$reactionPath]['outbound'][$outboundIndex] = array(
                'code' => '',
                'value_attr' => '',
                'value_code' => '',
                'value_text' => ''
              );
            }
            if ($outboundSubPath === 'code[1]/@code') {
              $reactionGroups[$reactionPath]['outbound'][$outboundIndex]['code'] = $fieldValue;
            } elseif ($outboundSubPath === 'value[1]/@value') {
              $reactionGroups[$reactionPath]['outbound'][$outboundIndex]['value_attr'] = $fieldValue;
            } elseif ($outboundSubPath === 'value[1]/@code') {
              $reactionGroups[$reactionPath]['outbound'][$outboundIndex]['value_code'] = $fieldValue;
            } elseif ($outboundSubPath === 'value[1]') {
              $reactionGroups[$reactionPath]['outbound'][$outboundIndex]['value_text'] = $fieldValue;
            }
          }
        }
      }

      $drugPathMatches = array();
      if (preg_match('#^(.*?/subjectof2\[\d+\]/organizer\[\d+\]/component\[\d+\]/substanceadministration\[\d+\])/(.+)$#i', $fieldPath, $drugPathMatches)) {
        $drugPath = $drugPathMatches[1];
        $drugSubPath = strtolower($drugPathMatches[2]);
        if (!isset($drugGroups[$drugPath])) {
          $drugGroups[$drugPath] = array(
            'sequence' => !empty($field['sequence']) ? (int) $field['sequence'] : 999999,
            'medicinal_product_name' => '',
            'active_substance_name' => '',
            'strength_value' => '',
            'strength_unit' => '',
            'dosage_text' => '',
            'interval_value' => '',
            'interval_unit' => '',
            'drug_start_date' => '',
            'dosage_form' => '',
            'indication_code' => '',
            'indication_text' => '',
            'auth_application_number' => '',
            'holder_name' => '',
            'authorisation_country' => '',
            'drug_obtained_country' => ''
          );
        } elseif (!empty($field['sequence']) && (int) $field['sequence'] < $drugGroups[$drugPath]['sequence']) {
          $drugGroups[$drugPath]['sequence'] = (int) $field['sequence'];
        }

        if ($drugSubPath === 'consumable[1]/instanceofkind[1]/kindofproduct[1]/name[1]') {
          $drugGroups[$drugPath]['medicinal_product_name'] = $fieldValue;
        } elseif ($drugSubPath === 'consumable[1]/instanceofkind[1]/kindofproduct[1]/ingredient[1]/ingredientsubstance[1]/name[1]') {
          $drugGroups[$drugPath]['active_substance_name'] = $fieldValue;
        } elseif ($drugSubPath === 'consumable[1]/instanceofkind[1]/kindofproduct[1]/ingredient[1]/quantity[1]/numerator[1]/@value') {
          $drugGroups[$drugPath]['strength_value'] = $fieldValue;
        } elseif ($drugSubPath === 'consumable[1]/instanceofkind[1]/kindofproduct[1]/ingredient[1]/quantity[1]/numerator[1]/@unit') {
          $drugGroups[$drugPath]['strength_unit'] = $fieldValue;
        } elseif ($drugSubPath === 'consumable[1]/instanceofkind[1]/kindofproduct[1]/asmanufacturedproduct[1]/subjectof[1]/approval[1]/id[1]/@extension') {
          $drugGroups[$drugPath]['auth_application_number'] = $fieldValue;
        } elseif ($drugSubPath === 'consumable[1]/instanceofkind[1]/kindofproduct[1]/asmanufacturedproduct[1]/subjectof[1]/approval[1]/holder[1]/role[1]/playingorganization[1]/name[1]') {
          $drugGroups[$drugPath]['holder_name'] = $fieldValue;
        } elseif ($drugSubPath === 'consumable[1]/instanceofkind[1]/kindofproduct[1]/asmanufacturedproduct[1]/subjectof[1]/approval[1]/author[1]/territorialauthority[1]/territory[1]/code[1]/@code') {
          $drugGroups[$drugPath]['authorisation_country'] = $fieldValue;
        } elseif ($drugSubPath === 'consumable[1]/instanceofkind[1]/subjectof[1]/productevent[1]/performer[1]/assignedentity[1]/representedorganization[1]/addr[1]/country[1]') {
          $drugGroups[$drugPath]['drug_obtained_country'] = $fieldValue;
        } elseif ($drugSubPath === 'outboundrelationship2[1]/substanceadministration[1]/text[1]') {
          $drugGroups[$drugPath]['dosage_text'] = $fieldValue;
        } elseif ($drugSubPath === 'outboundrelationship2[1]/substanceadministration[1]/effectivetime[1]/comp[1]/period[1]/@value') {
          $drugGroups[$drugPath]['interval_value'] = $fieldValue;
        } elseif ($drugSubPath === 'outboundrelationship2[1]/substanceadministration[1]/effectivetime[1]/comp[1]/period[1]/@unit') {
          $drugGroups[$drugPath]['interval_unit'] = $fieldValue;
        } elseif ($drugSubPath === 'outboundrelationship2[1]/substanceadministration[1]/effectivetime[1]/comp[2]/low[1]/@value') {
          $drugGroups[$drugPath]['drug_start_date'] = $fieldValue;
        } elseif ($drugSubPath === 'outboundrelationship2[1]/substanceadministration[1]/consumable[1]/instanceofkind[1]/kindofproduct[1]/formcode[1]/originaltext[1]') {
          $drugGroups[$drugPath]['dosage_form'] = $fieldValue;
        } elseif ($drugSubPath === 'inboundrelationship[1]/observation[1]/value[1]/@code') {
          $drugGroups[$drugPath]['indication_code'] = $fieldValue;
        } elseif ($drugSubPath === 'inboundrelationship[1]/observation[1]/value[1]/originaltext[1]') {
          $drugGroups[$drugPath]['indication_text'] = $fieldValue;
        }
      }

      $isPatientPath = (
        strpos($fieldPath, '/subject1[1]/primaryrole[1]/player1') !== false ||
        strpos($fieldPath, 'administrativegendercode') !== false ||
        strpos($fieldPath, '/subjectof2[1]/organizer[1]/component[') !== false ||
        strpos($fieldPath, 'patient') !== false
      );
      $isDrugPath = (strpos($fieldPath, 'substanceadministration') !== false);
      $isReactionPath = (bool) preg_match('#/subjectof2\[(2|3|4|5|6)\]/observation\[1\]#', $fieldPath);

      if (!$isPatientPath && !$isDrugPath && !$isReactionPath) {
        if ($isAttribute && !in_array($fieldKey, array('@value', '@extension', '@displayname', '@code'))) {
          continue;
        }
        $fingerprint = $field['field_path'] . '|' . $fieldValue;
        if (isset($seenReportedRows[$fingerprint])) {
          continue;
        }
        $seenReportedRows[$fingerprint] = true;
        if (count($reportedInformationRows) >= $reportedMaxRows) {
          continue;
        }
        $field['display_label'] = $normalizeLabel($field);
        $reportedInformationRows[] = $field;
      }
    }

    $genderLookup = array('1' => 'Male', '2' => 'Female', '0' => 'Unknown', '9' => 'Unknown');
    if (!empty($patientProfile['Sex']) && isset($genderLookup[$patientProfile['Sex']])) {
      $patientProfile['Sex'] = $patientProfile['Sex'] . ' (' . $genderLookup[$patientProfile['Sex']] . ')';
    }

    $patientObservationRows = array();
    ksort($patientObservationGroups);
    foreach ($patientObservationGroups as $observationIndex => $observationGroup) {
      $patientObservationRows[] = array(
        'observation_no' => $observationIndex,
        'comment_meaning' => 'Relevant medical history and concurrent conditions (not including reaction/event)',
        'meddra_code' => $observationGroup['meddra_code'],
        'start_date' => $observationGroup['start_date']
      );
    }

    $drugSummaries = array();
    uasort($drugGroups, function ($left, $right) {
      if ($left['sequence'] === $right['sequence']) {
        return 0;
      }
      return ($left['sequence'] < $right['sequence']) ? -1 : 1;
    });
    foreach ($drugGroups as $drugGroup) {
      if ($firstNonEmpty(array(
        $drugGroup['medicinal_product_name'],
        $drugGroup['active_substance_name'],
        $drugGroup['dosage_text'],
        $drugGroup['indication_code'],
        $drugGroup['indication_text']
      )) === '') {
        continue;
      }
      $strength = trim(trim($drugGroup['strength_value']) . ' ' . trim($drugGroup['strength_unit']));
      $interval = trim(trim($drugGroup['interval_value']) . ' ' . trim($drugGroup['interval_unit']));
      $drugSummaries[] = array(
        'medicinal_product_name' => $drugGroup['medicinal_product_name'],
        'active_substance_name' => $drugGroup['active_substance_name'],
        'strength' => $strength,
        'dosage_text' => $drugGroup['dosage_text'],
        'interval' => $interval,
        'drug_start_date' => $drugGroup['drug_start_date'],
        'dosage_form' => $drugGroup['dosage_form'],
        'indication_code' => $drugGroup['indication_code'],
        'indication_text' => $drugGroup['indication_text'],
        'auth_application_number' => $drugGroup['auth_application_number'],
        'holder_name' => $drugGroup['holder_name'],
        'authorisation_country' => $drugGroup['authorisation_country'],
        'drug_obtained_country' => $drugGroup['drug_obtained_country']
      );
    }

    $reactionSummaries = array();
    uasort($reactionGroups, function ($left, $right) {
      if ($left['sequence'] === $right['sequence']) {
        return 0;
      }
      return ($left['sequence'] < $right['sequence']) ? -1 : 1;
    });
    foreach ($reactionGroups as $reactionGroup) {
      if (trim((string) $reactionGroup['event_code']) !== '29') {
        continue;
      }
      $summary = array(
        'meddra_code' => $reactionGroup['meddra_code'],
        'meddra_term' => $reactionGroup['meddra_term'],
        'start_date' => $reactionGroup['start_date'],
        'country_code' => $reactionGroup['country_code'],
        'reported_text' => '',
        'outcome' => '',
        'medical_confirmation' => null,
        'seriousness' => array(
          'death' => null,
          'life_threatening' => null,
          'hospitalisation' => null,
          'disabling' => null,
          'congenital' => null,
          'other_medically_important' => null
        )
      );

      if (!empty($reactionGroup['outbound'])) {
        ksort($reactionGroup['outbound']);
        foreach ($reactionGroup['outbound'] as $outboundItem) {
          $detailCode = trim((string) $outboundItem['code']);
          $valueAttr = trim((string) $outboundItem['value_attr']);
          $valueCode = trim((string) $outboundItem['value_code']);
          $valueText = trim((string) $outboundItem['value_text']);

          if ($detailCode === '30') {
            $summary['reported_text'] = $firstNonEmpty(array($valueText, $valueCode, $valueAttr));
          } elseif ($detailCode === '27') {
            $outcomeCode = $firstNonEmpty(array($valueCode, $valueAttr, $valueText));
            $summary['outcome'] = isset($outcomes[$outcomeCode]) ? $outcomes[$outcomeCode] : $outcomeCode;
          } elseif ($detailCode === '24') {
            $summary['medical_confirmation'] = $toBooleanState($valueAttr);
          } elseif ($detailCode === '34') {
            $summary['seriousness']['death'] = $toBooleanState($valueAttr);
          } elseif ($detailCode === '21') {
            $summary['seriousness']['life_threatening'] = $toBooleanState($valueAttr);
          } elseif ($detailCode === '33') {
            $summary['seriousness']['hospitalisation'] = $toBooleanState($valueAttr);
          } elseif ($detailCode === '35') {
            $summary['seriousness']['disabling'] = $toBooleanState($valueAttr);
          } elseif ($detailCode === '12') {
            $summary['seriousness']['congenital'] = $toBooleanState($valueAttr);
          } elseif ($detailCode === '26') {
            $summary['seriousness']['other_medically_important'] = $toBooleanState($valueAttr);
          }
        }
      }
      $reactionSummaries[] = $summary;
    }

    $drugRowDefinitions = array(
      array('label' => 'Medicinal Product Name (Primary Source)', 'key' => 'medicinal_product_name'),
      array('label' => 'Substance/Specified Substance Name', 'key' => 'active_substance_name'),
      array('label' => 'Strength (Number + Unit)', 'key' => 'strength'),
      array('label' => 'Dosage Text', 'key' => 'dosage_text'),
      array('label' => 'Number of Units in Interval + Time Unit', 'key' => 'interval'),
      array('label' => 'Start Date of Drug', 'key' => 'drug_start_date'),
      array('label' => 'Pharmaceutical Form', 'key' => 'dosage_form'),
      array('label' => 'Indication (MedDRA code)', 'key' => 'indication_code'),
      array('label' => 'Indication (Primary Source)', 'key' => 'indication_text'),
      array('label' => 'Authorisation/Application Number', 'key' => 'auth_application_number'),
      array('label' => 'Holder/Applicant Name', 'key' => 'holder_name'),
      array('label' => 'Country of Authorisation/Application', 'key' => 'authorisation_country'),
      array('label' => 'Country Where Drug Was Obtained', 'key' => 'drug_obtained_country')
    );

    $reactionRowDefinitions = array(
      array('label' => 'Reaction/Event (MedDRA code)', 'key' => 'meddra_code', 'type' => 'text'),
      array('label' => 'Reaction/Event Term', 'key' => 'meddra_term', 'type' => 'text'),
      array('label' => 'Date of Start of Reaction/Event', 'key' => 'start_date', 'type' => 'date'),
      array('label' => 'Country Where Reaction/Event Occurred', 'key' => 'country_code', 'type' => 'text'),
      array('label' => 'Reaction/Event as Reported by Primary Source', 'key' => 'reported_text', 'type' => 'text'),
      array('label' => 'Outcome of Reaction/Event', 'key' => 'outcome', 'type' => 'text'),
      array('label' => 'Medical Confirmation by Healthcare Professional', 'key' => 'medical_confirmation', 'type' => 'bool'),
      array('label' => 'Results in Death', 'key' => 'death', 'type' => 'seriousness_bool'),
      array('label' => 'Life Threatening', 'key' => 'life_threatening', 'type' => 'seriousness_bool'),
      array('label' => 'Caused/Prolonged Hospitalisation', 'key' => 'hospitalisation', 'type' => 'seriousness_bool'),
      array('label' => 'Disabling/Incapacitating', 'key' => 'disabling', 'type' => 'seriousness_bool'),
      array('label' => 'Congenital Anomaly/Birth Defect', 'key' => 'congenital', 'type' => 'seriousness_bool'),
      array('label' => 'Other Medically Important Condition', 'key' => 'other_medically_important', 'type' => 'seriousness_bool')
    );
?>
<div class="ciom-form">
    <hr>
    <h4 style="text-decoration: underline;"> <?php echo h($ciom['Application']['protocol_no']); ?> </h4>
    <?php
      echo $this->requestAction('/applications/study_title/'.$ciom['Ciom']['application_id']);
      echo $this->Html->link(
                $ciom['Ciom']['basename'],
                str_replace('/var/www/ctr/app/webroot', '', $ciom['Ciom']['file']),
                array('class' => 'button', 'target' => '_blank')
            );
    ?>
    <h4 class="text-center" style="text-align: center; text-decoration: underline;">CIOMS E2B(R3)</h4>
    <div class="alert alert-info">
      <strong>Detected E2B(R3) message.</strong> Sections are mapped to E2B comment meanings.
    </div>

    <h4 style="margin-top: 20px;">Patient Information</h4>
    <table class="table table-condensed table-bordered">
      <thead><tr style="background: #DAEDF3;"><th style="width: 38%;">Field</th><th>Value</th></tr></thead>
      <tbody>
        <?php foreach ($patientProfile as $profileLabel => $profileValue) { ?>
          <?php if (trim((string) $profileValue) === '') { continue; } ?>
          <tr>
            <td><?php echo h($profileLabel); ?></td>
            <td><?php echo h($profileValue); ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
    <?php if (!empty($patientObservationRows)) { ?>
      <table class="table table-condensed table-bordered">
        <thead>
          <tr style="background: #DAEDF3;">
            <th style="width: 60%;">Comment Meaning</th>
            <th style="width: 20%;">MedDRA Code</th>
            <th style="width: 20%;">Start Date</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($patientObservationRows as $patientObservationRow) { ?>
            <tr>
              <td><?php echo h($patientObservationRow['comment_meaning']); ?></td>
              <td><?php echo h($patientObservationRow['meddra_code']); ?></td>
              <td><?php echo h($formatE2bDate($patientObservationRow['start_date'])); ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    <?php } else { ?>
      <p class="muted">No patient observation rows detected.</p>
    <?php } ?>

    <h4>Drugs Section</h4>
    <?php if (!empty($drugSummaries)) { ?>
      <div class="table-responsive">
        <table class="table table-condensed table-bordered">
          <thead>
            <tr style="background: #DAEDF3;">
              <?php foreach ($drugRowDefinitions as $drugRowDefinition) { ?>
                <th><?php echo h($drugRowDefinition['label']); ?></th>
              <?php } ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($drugSummaries as $drugIndex => $drugSummary) { ?>
              <tr>
                <?php foreach ($drugRowDefinitions as $drugRowDefinition) { ?>
                  <?php $drugValue = isset($drugSummary[$drugRowDefinition['key']]) ? $drugSummary[$drugRowDefinition['key']] : ''; ?>
                  <?php if ($drugRowDefinition['key'] === 'drug_start_date') { $drugValue = $formatE2bDate($drugValue); } ?>
                  <td><?php echo nl2br(h($drugValue)); ?></td>
                <?php } ?>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    <?php } else { ?>
      <p class="muted">No drug details detected in this E2B(R3) message.</p>
    <?php } ?>

    <h4>Reactions Section</h4>
    <?php if (!empty($reactionSummaries)) { ?>
      <div class="table-responsive">
        <table class="table table-condensed table-bordered">
          <thead>
            <tr style="background: #DAEDF3;">
              <?php foreach ($reactionRowDefinitions as $reactionRowDefinition) { ?>
                <th><?php echo h($reactionRowDefinition['label']); ?></th>
              <?php } ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($reactionSummaries as $reactionIndex => $reactionSummary) { ?>
              <tr>
                <?php foreach ($reactionRowDefinitions as $reactionRowDefinition) { ?>
                  <?php
                    $reactionCellValue = '';
                    if ($reactionRowDefinition['type'] === 'seriousness_bool') {
                      $reactionCellValue = isset($reactionSummary['seriousness'][$reactionRowDefinition['key']]) ? $reactionSummary['seriousness'][$reactionRowDefinition['key']] : null;
                    } else {
                      $reactionCellValue = isset($reactionSummary[$reactionRowDefinition['key']]) ? $reactionSummary[$reactionRowDefinition['key']] : '';
                    }
                  ?>
                  <td>
                    <?php if ($reactionRowDefinition['type'] === 'bool' || $reactionRowDefinition['type'] === 'seriousness_bool') { ?>
                      <input type="checkbox" disabled="disabled" <?php echo ($reactionCellValue === true) ? 'checked="checked"' : ''; ?> />
                      <?php echo h($booleanText($reactionCellValue)); ?>
                    <?php } elseif ($reactionRowDefinition['type'] === 'date') { ?>
                      <?php echo h($formatE2bDate($reactionCellValue)); ?>
                    <?php } else { ?>
                      <?php echo nl2br(h($reactionCellValue)); ?>
                    <?php } ?>
                  </td>
                <?php } ?>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    <?php } else { ?>
      <p class="muted">No reaction details detected in this E2B(R3) message.</p>
    <?php } ?>

    <h4>Reported Information</h4>
    <?php
      $hasReportedSummary = false;
      foreach ($reportedSummary as $summaryValue) {
        if (trim((string) $summaryValue) !== '') {
          $hasReportedSummary = true;
          break;
        }
      }
      $hasNarrative = (trim((string) $reportedCaseNarrative) !== '');
    ?>
    <?php if ($hasReportedSummary) { ?>
      <table class="table table-condensed table-bordered">
        <thead><tr style="background: #DAEDF3;"><th style="width: 30%;">Field</th><th>Value</th></tr></thead>
        <tbody>
          <?php foreach ($reportedSummary as $summaryLabel => $summaryValue) { ?>
            <?php if (trim((string) $summaryValue) === '') { continue; } ?>
            <?php
              $displaySummaryValue = $summaryValue;
              if (in_array($summaryLabel, array('First Received Date', 'Most Recent Info Date'))) {
                $displaySummaryValue = $formatE2bDate($summaryValue);
              } elseif ($summaryLabel === 'Message Creation Date') {
                $displaySummaryValue = $formatE2bTimestamp($summaryValue);
              }
            ?>
            <tr>
              <td><?php echo h($summaryLabel); ?></td>
              <td><?php echo nl2br(h($displaySummaryValue)); ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    <?php } ?>

    <?php if ($hasNarrative) { ?>
      <h5>Case Narrative</h5>
      <div class="well well-sm" style="white-space: normal; line-height: 1.6;">
        <?php echo nl2br(h($reportedCaseNarrative)); ?>
      </div>
    <?php } ?>

    <?php if (!empty($reportedInformationRows)) { ?>
      <details style="margin-top: 10px;">
        <summary><strong>Additional Extracted Details</strong></summary>
        <table class="table table-condensed table-bordered" style="margin-top: 8px;">
          <thead><tr style="background: #DAEDF3;"><th style="width: 30%;">Field</th><th>Value</th></tr></thead>
          <tbody>
            <?php foreach ($reportedInformationRows as $reportedInformationRow) { ?>
              <tr>
                <td><?php echo h($reportedInformationRow['display_label']); ?></td>
                <td><?php echo nl2br(h($reportedInformationRow['field_value'])); ?></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </details>
    <?php } ?>

    <?php if (!$hasReportedSummary && !$hasNarrative && empty($reportedInformationRows)) { ?>
      <p class="muted">No reported information detected in this E2B(R3) message.</p>
    <?php } ?>
</div>
<?php
    return;
}
?>

<div class="ciom-form">    

    <hr>
    <h4 style="text-decoration: underline;"> <?php echo $ciom['Application']['protocol_no']; ?> </h4>
    <?php
      echo $this->requestAction('/applications/study_title/'.$ciom['Ciom']['application_id']);
      echo $this->Html->link(
                $ciom['Ciom']['basename'],
                str_replace('/var/www/ctr/app/webroot', '', $ciom['Ciom']['file']),
                array('class' => 'button', 'target' => '_blank')
            );
    ?>
    <h4 class="text-center"  style="text-align: center; text-decoration: underline;">CIOMS FORM</h4>
    <table class="table  table-condensed">
      <thead>
      <tr style="background: #C5D9F0;">
        <th>CIOM Form</th>
        <th>ICH-E2B field (R2)</th>
      </tr>        
      </thead>
      <tbody>
        <tr style="background: #DAEDF3;"><td colspan="2"> I. REACTION INFORMATION </td> </tr>
        <tr>
          <td width="30%" class="table-label required"><p>1. Patient Initials <small class="muted">(first, last)</small> </p></td>
          <td><?php  
          // debug(Hash::extract($e2b, 'ichicsr.safetyreport.patient.patientinitial'));
          // debug(Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.patientinitial'));
          echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.patientinitial'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.patientinitial')));
          //echo (!empty($e2b['ichicsr']['safetyreport']['patient']['patientinitial'])) ? $e2b['ichicsr']['safetyreport']['patient']['patientinitial'] : null; ?></td>
        </tr>
        <tr>
          <td class="table-label required"><p>1.a Country</p></td>
          <td><?php  
            echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.occurcountry'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.occurcountry')));
            //echo (!empty($e2b['ichicsr']['safetyreport']['occurcountry'])) ? $e2b['ichicsr']['safetyreport']['occurcountry'] : null; ?></td>
        </tr>
        <tr>
          <td class="table-label required"><p>2. Date of birth</p></td>
          <td><?php  
            echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.patientbirthdate'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.patientbirthdate')));
          //echo (!empty($e2b['ichicsr']['safetyreport']['patient']['patientbirthdate'])) ? $e2b['ichicsr']['safetyreport']['patient']['patientbirthdate'] : null; ?></td>
        </tr>
        <tr>
          <td class="table-label required"><p>2.a Age <small class="muted">(years)</small> </p></td>
          <td><?php  
            echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.patientonsetage'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.patientonsetage')));
          //echo (!empty($e2b['ichicsr']['safetyreport']['patient']['patientonsetage'])) ? $e2b['ichicsr']['safetyreport']['patient']['patientonsetage'] : null; ?></td>
        </tr>
        <tr>
          <td class="table-label required"><p>2. Sex </p></td>
          <td><?php  
            echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.patientsex'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.patientsex')));
          //echo (!empty($e2b['ichicsr']['safetyreport']['patient']['patientsex'])) ? $e2b['ichicsr']['safetyreport']['patient']['patientsex'] : null; ?></td>
        </tr>
        <tr>
          <td class="table-label required"><p>4-6. Reaction onset </p></td>
          <td><?php  echo (!empty($e2b['ichicsr']['safetyreport']['patient']['reaction']['reactionstartdate'])) ? $e2b['ichicsr']['safetyreport']['patient']['reaction']['reactionstartdate'] : null; ?></td>
        </tr>
        <tr>
          <td class="table-label required"><p>7. Describe reaction(s) </p></td>
          <td>
            <?php
              echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.reaction.primarysourcereaction'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.reaction.{n}.primarysourcereaction')));
              // echo (!empty($e2b['ichicsr']['safetyreport']['patient']['reaction']['primarysourcereaction'])) ? $e2b['ichicsr']['safetyreport']['patient']['reaction']['primarysourcereaction'] : null; 
              echo "<br/>";
              $out = array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.reaction.reactionoutcome'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.reaction.{n}.reactionoutcome'));
              array_walk($out, function(&$value, &$key) use ($outcomes) {
                    $value = (isset($outcomes[$value])) ? $outcomes[$value] : $value;
                });
              echo implode(" | ", $out);
              // echo (!empty($e2b['ichicsr']['safetyreport']['patient']['reaction']['reactionoutcome'])) ? $outcomes[$e2b['ichicsr']['safetyreport']['patient']['reaction']['reactionoutcome']] : null; 
              echo "<br/>";
              $ad = array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.drug.actiondrug'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.drug.{n}.actiondrug'));
              array_walk($ad, function(&$value, &$key) use ($actiondrug) {
                    $value = (isset($actiondrug[$value])) ? $actiondrug[$value] : $value;
                });
              echo implode(" | ", $ad);
              echo "<br/>";
              echo implode(" <br>|<br> ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.summary.narrativeincludeclinical'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.summary.narrativeincludeclinical')));
              // echo (!empty($e2b['ichicsr']['safetyreport']['patient']['summary']['narrativeincludeclinical'])) ? $e2b['ichicsr']['safetyreport']['patient']['summary']['narrativeincludeclinical'] : null; 
              // echo (!empty($e2b['ichicsr']['safetyreport']['patient']['reaction']['primarysourcereaction'])) ? $e2b['ichicsr']['safetyreport']['patient']['reaction']['primarysourcereaction'] : null; 
              // echo "<br/>";
              // echo (!empty($e2b['ichicsr']['safetyreport']['patient']['reaction']['reactionoutcome'])) ? $outcomes[$e2b['ichicsr']['safetyreport']['patient']['reaction']['reactionoutcome']] : null; 
              // echo "<br/>";
              // echo (!empty($e2b['ichicsr']['safetyreport']['patient']['drug']['actiondrug'])) ? $actiondrug[$e2b['ichicsr']['safetyreport']['patient']['drug']['actiondrug']] : null; 
              // echo "<br/>";
              // echo (!empty($e2b['ichicsr']['safetyreport']['patient']['summary']['narrativeincludeclinical'])) ? $e2b['ichicsr']['safetyreport']['patient']['summary']['narrativeincludeclinical'] : null; 
            ?>
          </td>
        </tr>
        <tr>
          <td class="table-label required"><p>13. (including relevant test lab data) </p></td>
          <td>
            <?php  
              echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.resultstestsprocedures'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.resultstestsprocedures')));
              // echo (!empty($e2b['ichicsr']['safetyreport']['patient']['resultstestsprocedures'])) ? $e2b['ichicsr']['safetyreport']['patient']['resultstestsprocedures'] : null; 
              ?>
          </td>
        </tr>
        <tr>
          <td class="table-label required"><p>8-12. Check all appropriate to adverse reaction </p></td>
          <td><p>Serious - at case level? </p>
            <?php  
              //echo (!empty($e2b['ichicsr']['safetyreport']['serious'])) ? $serious[$e2b['ichicsr']['safetyreport']['serious']] : null;
              $se = array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.serious'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.serious'));
              array_walk($se, function(&$value, &$key) use ($serious) {
                    $value = (isset($serious[$value])) ? $serious[$value] : $value;
                });
              echo implode(" | ", $se);
            ?>
          </td>
        </tr>
        <tr>
          <td><p>Patient died </p></td>
          <td>
            <?php  
              //echo (!empty($e2b['ichicsr']['safetyreport']['seriousnessdeath'])) ? $serious[$e2b['ichicsr']['safetyreport']['seriousnessdeath']] : null; 
              $de = array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.seriousnessdeath'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.seriousnessdeath'));
              array_walk($de, function(&$value, &$key) use ($serious) {
                    $value = (isset($serious[$value])) ? $serious[$value] : $value;
                });
              echo implode(" | ", $de);
            ?>              
          </td>
        </tr>
        <tr>
          <td><p>Involved or prolonged inpatient hospitalization </p></td>
          <td>
            <?php 
              // echo (!empty($e2b['ichicsr']['safetyreport']['seriousnesshospitalization'])) ? $serious[$e2b['ichicsr']['safetyreport']['seriousnesshospitalization']] : null; 
              $ho = array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.seriousnesshospitalization'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.seriousnesshospitalization'));
              array_walk($ho, function(&$value, &$key) use ($serious) {
                    $value = (isset($serious[$value])) ? $serious[$value] : $value;
                });
              echo implode(" | ", $ho);
            ?>              
          </td>
        </tr>
        <tr>
          <td><p>Involved persistence or significant disability or incapacity </p></td>
          <td><?php  
            // echo (!empty($e2b['ichicsr']['safetyreport']['seriousnessdisabling'])) ? $serious[$e2b['ichicsr']['safetyreport']['seriousnessdisabling']] : null; 
            $bl = array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.seriousnessdisabling'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.seriousnessdisabling'));
              array_walk($bl, function(&$value, &$key) use ($serious) {
                    $value = (isset($serious[$value])) ? $serious[$value] : $value;
                });
              echo implode(" | ", $bl);
            ?></td>
        </tr>
        <tr>
          <td><p>Life threatening </p></td>
          <td><?php  
            // echo (!empty($e2b['ichicsr']['safetyreport']['seriousnesslifethreatening'])) ? $serious[$e2b['ichicsr']['safetyreport']['seriousnesslifethreatening']] : null; 
            $lt = array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.seriousnesslifethreatening'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.seriousnesslifethreatening'));
              array_walk($lt, function(&$value, &$key) use ($serious) {
                    $value = (isset($serious[$value])) ? $serious[$value] : $value;
                });
              echo implode(" | ", $lt);
            ?></td>
        </tr>
      </tbody>
    </table>

    <table class="table  table-condensed">
      <thead>
        <tr style="background: #DAEDF3;">
          <th class="table-label required"><p>SUSPECT/CONCOMITANT DRUG(S) INFORMATION</p></th>
          <th>Drug characterization: <?php  
            // echo (!empty($e2b['ichicsr']['safetyreport']['patient']['drug']['drugcharacterization'])) ? $drugcharacterization[$e2b['ichicsr']['safetyreport']['patient']['drug']['drugcharacterization']] : null; 
            $dc = array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.drug.drugcharacterization'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.drug.{n}.drugcharacterization'));
              array_walk($dc, function(&$value, &$key) use ($drugcharacterization) {
                    $value = (isset($drugcharacterization[$value])) ? $drugcharacterization[$value] : $value;
                });
              echo implode(" | ", $dc);
            ?></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="table-label required"><p>14. Suspect drug(s)</p></td>
          <td><?php  
            // echo (!empty($e2b['ichicsr']['safetyreport']['patient']['drug']['medicinalproduct'])) ? $e2b['ichicsr']['safetyreport']['patient']['drug']['medicinalproduct'] : null; 
            echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.drug.medicinalproduct'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.drug.{n}.medicinalproduct')));
            ?></td>
        </tr>
        <tr>
          <td><p>Batch/lot number</p></td>
          <td><?php  
            // echo (!empty($e2b['ichicsr']['safetyreport']['patient']['drug']['drugbatchnumb'])) ? $e2b['ichicsr']['safetyreport']['patient']['drug']['drugbatchnumb'] : null; 
            echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.drug.drugbatchnumb'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.drug.{n}.drugbatchnumb')));
            ?></td>
        </tr>
        <tr>
          <td class="table-label required"><p>15. Daily dose(s)</p></td>
          <td><?php
            //echo (!empty($e2b['ichicsr']['safetyreport']['patient']['drug']['drugdosagetext'])) ? $e2b['ichicsr']['safetyreport']['patient']['drug']['drugdosagetext'] : null; 
            echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.drug.drugdosagetext'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.drug.{n}.drugdosagetext')));
          ?></td>
        </tr>
        <tr>
          <td class="table-label required"><p>16. Route(s) of administration</p></td>
          <td><?php  
            //echo (!empty($e2b['ichicsr']['safetyreport']['patient']['drug']['drugadministrationroute'])) ? $drugadministrationroute[$e2b['ichicsr']['safetyreport']['patient']['drug']['drugadministrationroute']] : null; 
            $ar = array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.drug.drugadministrationroute'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.drug.{n}.drugadministrationroute'));
              array_walk($ar, function(&$value, &$key) use ($drugadministrationroute) {
                    $value = (isset($drugadministrationroute[$value])) ? $drugadministrationroute[$value] : $value;
                });
              echo implode(" | ", $ar);
          ?></td>
        </tr>
        <tr>
          <td class="table-label required"><p>17. Indication(s) for use</p></td>
          <td><?php  
            //echo (!empty($e2b['ichicsr']['safetyreport']['patient']['drug']['drugindication'])) ? $e2b['ichicsr']['safetyreport']['patient']['drug']['drugindication'] : null; 
            echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.drug.drugindication'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.drug.{n}.drugindication')));
          ?></td>
        </tr>
        <tr>
          <td class="table-label required"><p>18. Therapy dates</p></td>
          <td>
            <p>Date of start of drug</p>
            <?php  
              //echo (!empty($e2b['ichicsr']['safetyreport']['patient']['drug']['drugstartdate'])) ? $e2b['ichicsr']['safetyreport']['patient']['drug']['drugstartdate'] : null; 
              echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.drug.drugstartdate'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.drug.{n}.drugstartdate')));
            ?>
          </td>
        </tr>
        <tr>
          <td class="table-label required"><p></p></td>
          <td>
          <p>Date of last administration</p>
          <?php  
            //echo (!empty($e2b['ichicsr']['safetyreport']['patient']['drug']['drugenddate'])) ? $e2b['ichicsr']['safetyreport']['patient']['drug']['drugenddate'] : null; 
            echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.drug.drugenddate'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.drug.{n}.drugenddate')));
          ?></td>
        </tr>
        <tr>
          <td class="table-label required"><p>19. Therapy duration</p></td>
          <td>
            <?php  
              // echo (!empty($e2b['ichicsr']['safetyreport']['patient']['drug']['drugtreatmentduration'])) ? $e2b['ichicsr']['safetyreport']['patient']['drug']['drugtreatmentduration'] : null; 
              echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.drug.drugtreatmentduration'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.drug.{n}.drugtreatmentduration')));
              echo "<br>";
              // echo (!empty($e2b['ichicsr']['safetyreport']['patient']['drug']['drugtreatmentdurationunit'])) ? $time_unit[$e2b['ichicsr']['safetyreport']['patient']['drug']['drugtreatmentdurationunit']] : null; 
              $dr = array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.drug.drugtreatmentdurationunit'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.drug.{n}.drugtreatmentdurationunit'));
              array_walk($dr, function(&$value, &$key) use ($time_unit) {
                    $value = (isset($time_unit[$value])) ? $time_unit[$value] : $value;
                });
              echo implode(" | ", $dr);
            ?>              
          </td>
        </tr>
          <tr>
            <td class="table-label required"><p>20. Did reaction abate after stopping drug?</p></td>
            <td><?php  
              //echo (!empty($e2b['ichicsr']['safetyreport']['patient']['drug']['actiondrug'])) ? $actiondrug[$e2b['ichicsr']['safetyreport']['patient']['drug']['actiondrug']] : null; 
            $ad = array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.drug.actiondrug'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.drug.{n}.actiondrug'));
              array_walk($ad, function(&$value, &$key) use ($actiondrug) {
                    $value = (isset($actiondrug[$value])) ? $actiondrug[$value] : $value;
                });
              echo implode(" | ", $ad);
            ?></td>
          </tr>
          <tr>
            <td class="table-label required"><p>21. Did reaction reappear after reintroduction?</p></td>
            <td><?php  
              //echo (!empty($e2b['ichicsr']['safetyreport']['patient']['drug']['drugrecurreadministration'])) ? $drugrecurreadministration[$e2b['ichicsr']['safetyreport']['patient']['drug']['drugrecurreadministration']] : null; 
              $re = array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.patient.drug.drugrecurreadministration'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.patient.drug.{n}.drugrecurreadministration'));
              array_walk($re, function(&$value, &$key) use ($drugrecurreadministration) {
                    $value = (isset($drugrecurreadministration[$value])) ? $drugrecurreadministration[$value] : $value;
                });
              echo implode(" | ", $re);
            ?></td>
          </tr>
      </tbody>
    </table>

    <table class="table  table-condensed">
      <thead>
        <tr style="background: #DAEDF3;"><th colspan="2" class="table-label required"><p>MANUFACTURER INFORMATION</p></th></tr>
      </thead>
      <tbody>
        <tr>
          <td class="table-label required"><p>Name and address of manufacturer</p></td>
          <td><?php  
            //echo (!empty($e2b['ichicsr']['safetyreport']['duplicatesource'])) ? $e2b['ichicsr']['safetyreport']['duplicatesource'] : null; 
            echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.duplicatesource'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.reportduplicate.{n}.duplicatesource')));
          ?></td>
        </tr>
        <tr>
          <td class="table-label required"><p>MFR control no.</p></td>
          <td><?php  
            //echo (!empty($e2b['ichicsr']['safetyreport']['duplicate'])) ? $e2b['ichicsr']['safetyreport']['duplicate'] : null; 
            echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.duplicate'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.duplicate')));
          ?></td>
        </tr>
        <tr>
          <td class="table-label required"><p>Date received by manufacturer</p></td>
          <td><?php 
            //echo (!empty($e2b['ichicsr']['safetyreport']['receiptdate'])) ? $e2b['ichicsr']['safetyreport']['receiptdate'] : null; 
            echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.receiptdate'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.receiptdate')));
          ?></td>
        </tr>
        <tr>
          <td class="table-label required"><p>Report source</p></td>
          <td>
            <p>Type of report</p>
            <?php  
              //echo (!empty($e2b['ichicsr']['safetyreport']['reporttype'])) ? $reporttype[$e2b['ichicsr']['safetyreport']['reporttype']] : null; 
              $re = array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.reporttype'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.reporttype'));
              array_walk($re, function(&$value, &$key) use ($reporttype) {
                    $value = (isset($reporttype[$value])) ? $reporttype[$value] : $value;
                });
              echo implode(" | ", $re);
            ?>
            <p>Literature reference(s)</p>
            <?php  
              //echo (!empty($e2b['ichicsr']['safetyreport']['primarysource']['literaturereference'])) ? $e2b['ichicsr']['safetyreport']['primarysource']['literaturereference'] : null; 
              echo implode(" | ", array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.primarysource.literaturereference'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.primarysource.{n}.literaturereference')));
            ?>
            <p>Qualification</p>
            <?php  
              //echo (!empty($e2b['ichicsr']['safetyreport']['primarysource']['qualification'])) ? $qualification[$e2b['ichicsr']['safetyreport']['primarysource']['qualification']] : null; 
              $qa = array_merge(Hash::extract($e2b, 'ichicsr.safetyreport.primarysource.qualification'), Hash::extract($e2b, 'ichicsr.safetyreport.{n}.primarysource.{n}.qualification'));
              array_walk($qa, function(&$value, &$key) use ($qualification) {
                    $value = (isset($qualification[$value])) ? $qualification[$value] : $value;
                });
              echo implode(" | ", $qa);
            ?>
          </td>
        </tr>
      </tbody>
    </table>
</div>
