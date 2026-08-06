-- phpMyAdmin SQL Dump
-- version 4.9.10
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 09, 2026 at 01:38 PM
-- Server version: 8.0.42-0ubuntu0.20.04.1
-- PHP Version: 5.6.40-63+ubuntu18.04.1+deb.sury.org+2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ct_staging`
--

-- --------------------------------------------------------

--
-- Table structure for table `acos`
--

CREATE TABLE `acos` (
  `id` int NOT NULL,
  `parent_id` int DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `foreign_key` int DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `lft` int DEFAULT NULL,
  `rght` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `active_inspectors`
--

CREATE TABLE `active_inspectors` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `application_id` int DEFAULT NULL,
  `type` char(30) DEFAULT NULL,
  `assessment_type` char(30) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `text` text,
  `summary` text,
  `recommendation` text,
  `notified` tinyint DEFAULT '0',
  `accepted` char(30) DEFAULT NULL,
  `conflict` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Unsubmitted',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `amendments`
--

CREATE TABLE `amendments` (
  `id` int NOT NULL,
  `application_id` int DEFAULT NULL,
  `trial_status_id` int DEFAULT NULL,
  `abstract_of_study` text,
  `study_title` text,
  `laymans_summary` text,
  `protocol_no` varchar(255) DEFAULT NULL,
  `version_no` varchar(255) DEFAULT NULL,
  `date_of_protocol` date DEFAULT NULL,
  `study_drug` varchar(255) DEFAULT NULL,
  `disease_condition` varchar(255) DEFAULT NULL,
  `product_type` text,
  `product_type_biologicals` tinyint(1) DEFAULT NULL,
  `product_type_proteins` tinyint(1) DEFAULT NULL,
  `product_type_immunologicals` tinyint(1) DEFAULT NULL,
  `product_type_vaccines` tinyint(1) DEFAULT NULL,
  `product_type_hormones` tinyint(1) DEFAULT NULL,
  `product_type_toxoid` tinyint(1) DEFAULT NULL,
  `product_type_chemical` tinyint(1) DEFAULT NULL,
  `product_type_medical_device` tinyint(1) DEFAULT NULL,
  `product_type_chemical_name` varchar(255) DEFAULT NULL,
  `product_type_medical_device_name` varchar(255) DEFAULT NULL,
  `comparator` varchar(30) DEFAULT NULL,
  `comparator_name` varchar(250) DEFAULT NULL,
  `comparator_registered` varchar(30) DEFAULT NULL,
  `comparator_countries` text,
  `previous_dates` text,
  `coordinating_investigators` text,
  `principal_investigators` text,
  `pharmacist` text,
  `sponsor_details` text,
  `gender` text,
  `details_of_sites` text,
  `placebos` text,
  `scopes` text,
  `organizations` text,
  `types_and_phases` text,
  `ecct_not_applicable` tinyint(1) DEFAULT '0',
  `ecct_ref_number` varchar(255) DEFAULT NULL,
  `email_address` varchar(255) DEFAULT NULL,
  `applicant_covering_letter` tinyint(1) DEFAULT NULL,
  `applicant_protocol` tinyint(1) DEFAULT NULL,
  `applicant_patient_information` tinyint(1) DEFAULT NULL,
  `applicant_investigators_brochure` tinyint(1) DEFAULT NULL,
  `applicant_investigators_cv` tinyint(1) DEFAULT NULL,
  `applicant_signed_declaration` tinyint(1) DEFAULT NULL,
  `applicant_financial_declaration` tinyint(1) DEFAULT NULL,
  `applicant_gmp_certificate` tinyint(1) DEFAULT NULL,
  `applicant_indemnity_cover` tinyint(1) DEFAULT NULL,
  `applicant_opinion_letter` tinyint(1) DEFAULT NULL,
  `applicant_approval_letter` tinyint(1) DEFAULT NULL,
  `applicant_statement` tinyint(1) DEFAULT NULL,
  `applicant_participating_countries` tinyint(1) DEFAULT NULL,
  `applicant_addendum` tinyint(1) DEFAULT NULL,
  `applicant_fees` tinyint(1) DEFAULT NULL,
  `declaration_applicant` varchar(255) DEFAULT NULL,
  `declaration_date1` date DEFAULT NULL,
  `declaration_principal_investigator` varchar(255) DEFAULT NULL,
  `declaration_date2` date DEFAULT NULL,
  `placebo_present` varchar(255) DEFAULT NULL,
  `study_objectives` text,
  `principal_inclusion_criteria` text,
  `principal_exclusion_criteria` text,
  `primary_end_points` text,
  `scope_diagnosis` tinyint(1) DEFAULT NULL,
  `scope_prophylaxis` tinyint(1) DEFAULT NULL,
  `scope_therapy` tinyint(1) DEFAULT NULL,
  `scope_safety` tinyint(1) DEFAULT NULL,
  `scope_efficacy` tinyint(1) DEFAULT NULL,
  `scope_pharmacokinetic` tinyint(1) DEFAULT NULL,
  `scope_pharmacodynamic` tinyint(1) DEFAULT NULL,
  `scope_bioequivalence` tinyint(1) DEFAULT NULL,
  `scope_dose_response` tinyint(1) DEFAULT NULL,
  `scope_pharmacogenetic` tinyint(1) DEFAULT NULL,
  `scope_pharmacogenomic` tinyint(1) DEFAULT NULL,
  `scope_pharmacoecomomic` tinyint(1) DEFAULT NULL,
  `scope_others` tinyint(1) DEFAULT NULL,
  `scope_others_specify` text,
  `trial_human_pharmacology` tinyint(1) DEFAULT NULL,
  `trial_administration_humans` tinyint(1) DEFAULT NULL,
  `trial_bioequivalence_study` tinyint(1) DEFAULT NULL,
  `trial_other` tinyint(1) DEFAULT NULL,
  `trial_other_specify` text,
  `trial_therapeutic_exploratory` tinyint(1) DEFAULT NULL,
  `trial_therapeutic_confirmatory` tinyint(1) DEFAULT NULL,
  `trial_therapeutic_use` tinyint(1) DEFAULT NULL,
  `design_controlled` varchar(255) DEFAULT NULL,
  `site_capacity` varchar(100) DEFAULT NULL,
  `staff_numbers` text,
  `other_details_explanation` text,
  `other_details_regulatory_notapproved` text,
  `other_details_regulatory_approved` text,
  `other_details_regulatory_rejected` text,
  `other_details_regulatory_halted` text,
  `estimated_duration` varchar(255) DEFAULT NULL,
  `design_controlled_randomised` varchar(255) DEFAULT NULL,
  `design_controlled_open` varchar(255) DEFAULT NULL,
  `design_controlled_single_blind` varchar(255) DEFAULT NULL,
  `design_controlled_double_blind` varchar(255) DEFAULT NULL,
  `design_controlled_parallel_group` varchar(255) DEFAULT NULL,
  `design_controlled_cross_over` varchar(255) DEFAULT NULL,
  `design_controlled_other` varchar(255) DEFAULT NULL,
  `design_controlled_specify` varchar(255) DEFAULT NULL,
  `design_controlled_comparator` varchar(255) DEFAULT NULL,
  `design_controlled_other_medicinal` varchar(255) DEFAULT NULL,
  `design_controlled_placebo` varchar(255) DEFAULT NULL,
  `design_controlled_medicinal_other` varchar(255) DEFAULT NULL,
  `design_controlled_medicinal_specify` varchar(255) DEFAULT NULL,
  `single_site_member_state` varchar(255) DEFAULT NULL,
  `location_of_area` varchar(255) DEFAULT NULL,
  `multiple_sites_member_state` varchar(255) DEFAULT NULL,
  `multiple_countries` char(30) DEFAULT NULL,
  `multiple_member_states` varchar(255) DEFAULT NULL,
  `number_of_sites` varchar(255) DEFAULT NULL,
  `multi_country_list` text,
  `data_monitoring_committee` varchar(255) DEFAULT NULL,
  `total_enrolment_per_site` text,
  `total_participants_worldwide` varchar(255) DEFAULT '',
  `population_less_than_18_years` varchar(255) DEFAULT NULL,
  `population_utero` varchar(255) DEFAULT NULL,
  `population_preterm_newborn` varchar(255) DEFAULT NULL,
  `population_newborn` varchar(255) DEFAULT NULL,
  `population_infant_and_toddler` varchar(255) DEFAULT NULL,
  `population_children` varchar(255) DEFAULT NULL,
  `population_adolescent` varchar(255) DEFAULT NULL,
  `population_above_18` char(30) DEFAULT NULL,
  `population_adult` varchar(255) DEFAULT NULL,
  `population_elderly` varchar(255) DEFAULT NULL,
  `gender_female` tinyint(1) DEFAULT NULL,
  `gender_male` tinyint(1) DEFAULT NULL,
  `subjects_healthy` varchar(255) DEFAULT NULL,
  `subjects_patients` varchar(255) DEFAULT NULL,
  `subjects_vulnerable_populations` varchar(255) DEFAULT NULL,
  `subjects_women_child_bearing` varchar(255) DEFAULT NULL,
  `subjects_women_using_contraception` varchar(255) DEFAULT NULL,
  `subjects_pregnant_women` varchar(255) DEFAULT NULL,
  `subjects_nursing_women` varchar(255) DEFAULT NULL,
  `subjects_emergency_situation` varchar(255) DEFAULT NULL,
  `subjects_incapable_consent` varchar(255) DEFAULT NULL,
  `subjects_specify` text,
  `subjects_others` varchar(255) DEFAULT NULL,
  `subjects_others_specify` text,
  `investigator1_given_name` varchar(255) DEFAULT NULL,
  `investigator1_middle_name` varchar(255) DEFAULT NULL,
  `investigator1_family_name` varchar(255) DEFAULT NULL,
  `investigator1_qualification` varchar(255) DEFAULT NULL,
  `investigator1_professional_address` varchar(255) DEFAULT NULL,
  `organisations_transferred_` varchar(255) DEFAULT NULL,
  `number_participants` text,
  `notification` text,
  `approval_date` date DEFAULT NULL,
  `submitted` tinyint(1) NOT NULL DEFAULT '0',
  `date_submitted` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `amendment_approvals`
--

CREATE TABLE `amendment_approvals` (
  `id` int NOT NULL,
  `application_id` int DEFAULT NULL,
  `approval_no` varchar(255) DEFAULT NULL,
  `amendment` varchar(255) DEFAULT NULL,
  `content` mediumtext,
  `approver` varchar(255) DEFAULT NULL,
  `approval_date` date DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `deleted_date` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `amendment_letters`
--

CREATE TABLE `amendment_letters` (
  `id` int NOT NULL,
  `application_id` int DEFAULT NULL,
  `approval_no` varchar(255) DEFAULT NULL,
  `content` mediumtext,
  `qrcode` text,
  `approver` varchar(255) DEFAULT NULL,
  `approval_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `submitted` tinyint DEFAULT '0',
  `deleted` tinyint(1) DEFAULT '0',
  `deleted_date` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `amends`
--

CREATE TABLE `amends` (
  `id` int NOT NULL,
  `amendment_id` int DEFAULT NULL,
  `application_id` int DEFAULT NULL,
  `cover_letter` longtext,
  `summary` longtext,
  `reason` longtext,
  `objectives_impacts` longtext,
  `endpoints_impacts` longtext,
  `safety_impacts` longtext,
  `submitted` tinyint NOT NULL DEFAULT '0',
  `date_submitted` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `annual_letters`
--

CREATE TABLE `annual_letters` (
  `id` int NOT NULL,
  `application_id` int DEFAULT NULL,
  `approval_no` varchar(255) DEFAULT NULL,
  `type` tinyint NOT NULL DEFAULT '0',
  `content` mediumtext,
  `qrcode` text,
  `approver` varchar(255) DEFAULT NULL,
  `approval_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `application_id` int DEFAULT NULL,
  `is_child` tinyint(1) NOT NULL DEFAULT '0',
  `trial_status_id` int DEFAULT NULL,
  `total_sites` varchar(255) DEFAULT NULL,
  `abstract_of_study` text,
  `study_title` text,
  `laymans_summary` text,
  `short_title` varchar(255) DEFAULT NULL,
  `protocol_no` varchar(55) DEFAULT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `version_no` varchar(55) DEFAULT NULL,
  `date_of_protocol` date DEFAULT NULL,
  `study_drug` varchar(255) DEFAULT NULL,
  `disease_condition` varchar(255) DEFAULT NULL,
  `product_type_biologicals` tinyint(1) DEFAULT NULL,
  `product_type_proteins` tinyint(1) DEFAULT NULL,
  `product_type_immunologicals` tinyint(1) DEFAULT NULL,
  `product_type_vaccines` tinyint(1) DEFAULT NULL,
  `product_type_hormones` tinyint(1) DEFAULT NULL,
  `product_type_toxoid` tinyint(1) DEFAULT NULL,
  `product_type_chemical` tinyint(1) DEFAULT NULL,
  `product_type_medical_device` tinyint(1) DEFAULT NULL,
  `product_type_chemical_name` varchar(255) DEFAULT NULL,
  `product_type_medical_device_name` varchar(255) DEFAULT NULL,
  `comparator` varchar(30) DEFAULT NULL,
  `comparator_name` varchar(250) DEFAULT NULL,
  `comparator_registered` varchar(30) DEFAULT NULL,
  `comparator_countries` text,
  `ecct_not_applicable` tinyint(1) DEFAULT '0',
  `ecct_ref_number` varchar(55) DEFAULT NULL,
  `email_address` varchar(55) DEFAULT NULL,
  `applicant_covering_letter` tinyint(1) DEFAULT NULL,
  `applicant_protocol` tinyint(1) DEFAULT NULL,
  `applicant_patient_information` tinyint(1) DEFAULT NULL,
  `applicant_investigators_brochure` tinyint(1) DEFAULT NULL,
  `applicant_investigators_cv` tinyint(1) DEFAULT NULL,
  `applicant_signed_declaration` tinyint(1) DEFAULT NULL,
  `applicant_financial_declaration` tinyint(1) DEFAULT NULL,
  `applicant_gmp_certificate` tinyint(1) DEFAULT NULL,
  `applicant_indemnity_cover` tinyint(1) DEFAULT NULL,
  `applicant_opinion_letter` tinyint(1) DEFAULT NULL,
  `applicant_approval_letter` tinyint(1) DEFAULT NULL,
  `applicant_statement` tinyint(1) DEFAULT NULL,
  `applicant_participating_countries` tinyint(1) DEFAULT NULL,
  `applicant_addendum` tinyint(1) DEFAULT NULL,
  `applicant_fees` tinyint(1) DEFAULT NULL,
  `applicant_complete_form` tinyint(1) DEFAULT NULL,
  `applicant_impd` tinyint(1) DEFAULT NULL,
  `applicant_prev_data` tinyint(1) DEFAULT NULL,
  `applicant_stability_data` tinyint(1) DEFAULT NULL,
  `applicant_analysis_cert` tinyint(1) DEFAULT NULL,
  `applicant_pictorial_sample` tinyint(1) DEFAULT NULL,
  `applicant_gcp_training` tinyint(1) DEFAULT NULL,
  `applicant_dsmb_charter` tinyint(1) DEFAULT NULL,
  `applicant_detailed_budget` tinyint(1) DEFAULT NULL,
  `applicant_indemnity_pi` tinyint(1) DEFAULT NULL,
  `applicant_practice_license` tinyint(1) DEFAULT NULL,
  `applicant_registration_ctr` tinyint(1) DEFAULT NULL,
  `applicant_pan_african` tinyint(1) DEFAULT NULL,
  `applicant_hard_copies` tinyint(1) DEFAULT NULL,
  `applicant_signed_checklist` tinyint(1) DEFAULT NULL,
  `statistical_analysis_plan` tinyint(1) DEFAULT NULL,
  `applicant_contractual_agreement` tinyint(1) DEFAULT NULL,
  `declaration_applicant` varchar(255) DEFAULT NULL,
  `declaration_date1` date DEFAULT NULL,
  `declaration_principal_investigator` varchar(255) DEFAULT NULL,
  `declaration_date2` date DEFAULT NULL,
  `placebo_present` varchar(45) DEFAULT NULL,
  `study_objectives` text,
  `principal_inclusion_criteria` text,
  `principal_exclusion_criteria` text,
  `primary_end_points` text,
  `scope_diagnosis` tinyint(1) DEFAULT NULL,
  `scope_prophylaxis` tinyint(1) DEFAULT NULL,
  `scope_therapy` tinyint(1) DEFAULT NULL,
  `scope_safety` tinyint(1) DEFAULT NULL,
  `scope_efficacy` tinyint(1) DEFAULT NULL,
  `scope_pharmacokinetic` tinyint(1) DEFAULT NULL,
  `scope_pharmacodynamic` tinyint(1) DEFAULT NULL,
  `scope_bioequivalence` tinyint(1) DEFAULT NULL,
  `scope_dose_response` tinyint(1) DEFAULT NULL,
  `scope_pharmacogenetic` tinyint(1) DEFAULT NULL,
  `scope_pharmacogenomic` tinyint(1) DEFAULT NULL,
  `scope_pharmacoecomomic` tinyint(1) DEFAULT NULL,
  `scope_others` tinyint(1) DEFAULT NULL,
  `scope_others_specify` text,
  `trial_human_pharmacology` tinyint(1) DEFAULT NULL,
  `trial_administration_humans` tinyint(1) DEFAULT NULL,
  `trial_bioequivalence_study` tinyint(1) DEFAULT NULL,
  `trial_other` tinyint(1) DEFAULT NULL,
  `trial_other_specify` text,
  `trial_therapeutic_exploratory` tinyint(1) DEFAULT NULL,
  `trial_therapeutic_confirmatory` tinyint(1) DEFAULT NULL,
  `trial_therapeutic_use` tinyint(1) DEFAULT NULL,
  `design_controlled` varchar(45) DEFAULT NULL,
  `site_capacity` varchar(50) DEFAULT NULL,
  `staff_numbers` text,
  `other_details_explanation` text,
  `other_details_regulatory_notapproved` text,
  `other_details_regulatory_approved` text,
  `other_details_regulatory_rejected` text,
  `other_details_regulatory_halted` text,
  `estimated_duration` varchar(255) DEFAULT NULL,
  `design_controlled_randomised` varchar(55) DEFAULT NULL,
  `design_controlled_open` varchar(25) DEFAULT NULL,
  `design_controlled_single_blind` varchar(25) DEFAULT NULL,
  `design_controlled_double_blind` varchar(25) DEFAULT NULL,
  `design_controlled_parallel_group` varchar(25) DEFAULT NULL,
  `design_controlled_cross_over` varchar(25) DEFAULT NULL,
  `design_controlled_other` varchar(25) DEFAULT NULL,
  `design_controlled_specify` varchar(255) DEFAULT NULL,
  `design_controlled_comparator` varchar(25) DEFAULT NULL,
  `design_controlled_other_medicinal` varchar(25) DEFAULT NULL,
  `design_controlled_placebo` varchar(25) DEFAULT NULL,
  `design_controlled_medicinal_other` varchar(25) DEFAULT NULL,
  `design_controlled_medicinal_specify` varchar(255) DEFAULT NULL,
  `single_site_member_state` varchar(255) DEFAULT NULL,
  `location_of_area` varchar(255) DEFAULT NULL,
  `single_site_physical_address` varchar(255) DEFAULT NULL,
  `single_site_contact_person` varchar(255) DEFAULT NULL,
  `single_site_telephone` varchar(255) DEFAULT NULL,
  `multiple_sites_member_state` varchar(255) DEFAULT NULL,
  `multiple_countries` char(30) DEFAULT NULL,
  `multiple_member_states` varchar(255) DEFAULT NULL,
  `number_of_sites` varchar(255) DEFAULT NULL,
  `multi_country_list` text,
  `data_monitoring_committee` varchar(255) DEFAULT NULL,
  `total_enrolment_per_site` text,
  `total_participants_worldwide` varchar(255) DEFAULT '',
  `population_less_than_18_years` varchar(15) DEFAULT NULL,
  `population_utero` varchar(15) DEFAULT NULL,
  `population_preterm_newborn` varchar(15) DEFAULT NULL,
  `population_newborn` varchar(15) DEFAULT NULL,
  `population_infant_and_toddler` varchar(15) DEFAULT NULL,
  `population_children` varchar(15) DEFAULT NULL,
  `population_adolescent` varchar(15) DEFAULT NULL,
  `population_above_18` char(30) DEFAULT NULL,
  `population_adult` varchar(15) DEFAULT NULL,
  `population_elderly` varchar(15) DEFAULT NULL,
  `gender_female` tinyint(1) DEFAULT NULL,
  `gender_male` tinyint(1) DEFAULT NULL,
  `subjects_healthy` varchar(15) DEFAULT NULL,
  `subjects_patients` varchar(15) DEFAULT NULL,
  `subjects_vulnerable_populations` varchar(15) DEFAULT NULL,
  `subjects_women_child_bearing` varchar(15) DEFAULT NULL,
  `subjects_women_using_contraception` varchar(15) DEFAULT NULL,
  `subjects_pregnant_women` varchar(15) DEFAULT NULL,
  `subjects_nursing_women` varchar(15) DEFAULT NULL,
  `subjects_emergency_situation` varchar(15) DEFAULT NULL,
  `subjects_incapable_consent` varchar(15) DEFAULT NULL,
  `subjects_specify` text,
  `subjects_others` varchar(255) DEFAULT NULL,
  `subjects_others_specify` text,
  `investigator1_given_name` varchar(255) DEFAULT NULL,
  `investigator1_middle_name` varchar(255) DEFAULT NULL,
  `investigator1_family_name` varchar(255) DEFAULT NULL,
  `investigator1_qualification` varchar(255) DEFAULT NULL,
  `investigator1_professional_address` varchar(255) DEFAULT NULL,
  `investigator1_telephone` varchar(255) DEFAULT NULL,
  `investigator1_email` varchar(255) DEFAULT NULL,
  `organisations_transferred_` varchar(255) DEFAULT NULL,
  `number_participants` text,
  `notification` text,
  `approval_date` date DEFAULT NULL,
  `submitted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `deactivated` tinyint(1) NOT NULL DEFAULT '0',
  `date_submitted` datetime DEFAULT NULL,
  `unsubmitted` tinyint DEFAULT '0',
  `initial_date_submitted` datetime DEFAULT NULL,
  `admin_stopped` tinyint DEFAULT '0',
  `admin_stopped_reason` text,
  `approved` tinyint NOT NULL DEFAULT '0',
  `approved_reason` text,
  `approved_date` varchar(255) DEFAULT NULL,
  `final_report` text,
  `implication_results` text,
  `quantity_imported` varchar(255) DEFAULT NULL,
  `quantity_dispensed` varchar(255) DEFAULT NULL,
  `quantity_destroyed` varchar(255) DEFAULT NULL,
  `quantity_exported` varchar(255) DEFAULT NULL,
  `balance_site` varchar(255) DEFAULT NULL,
  `final_date` date DEFAULT NULL,
  `ecitizen_invoice` varchar(255) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `ai_content` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 KEY_BLOCK_SIZE=8 ROW_FORMAT=COMPRESSED;

-- --------------------------------------------------------

--
-- Table structure for table `application_stages`
--

CREATE TABLE `application_stages` (
  `id` int NOT NULL,
  `application_id` int NOT NULL,
  `stage` varchar(100) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `status` char(30) DEFAULT NULL,
  `comment` text,
  `end_date` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `aros`
--

CREATE TABLE `aros` (
  `id` int NOT NULL,
  `parent_id` int DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `foreign_key` int DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `lft` int DEFAULT NULL,
  `rght` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `aros_acos`
--

CREATE TABLE `aros_acos` (
  `id` int NOT NULL,
  `aro_id` int NOT NULL,
  `aco_id` int NOT NULL,
  `_create` varchar(2) NOT NULL DEFAULT '0',
  `_read` varchar(2) NOT NULL DEFAULT '0',
  `_update` varchar(2) NOT NULL DEFAULT '0',
  `_delete` varchar(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `id` int UNSIGNED NOT NULL,
  `model` varchar(255) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `foreign_key` int NOT NULL,
  `dirname` varchar(255) DEFAULT NULL,
  `basename` varchar(255) NOT NULL,
  `checksum` varchar(255) NOT NULL,
  `alternative` varchar(50) DEFAULT NULL,
  `group` varchar(255) DEFAULT NULL,
  `description` text,
  `year` char(50) DEFAULT NULL,
  `file_date` date DEFAULT NULL,
  `pocket_name` varchar(255) DEFAULT NULL,
  `version_no` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `audit_trails`
--

CREATE TABLE `audit_trails` (
  `id` int NOT NULL,
  `foreign_key` int DEFAULT NULL,
  `model` varchar(50) NOT NULL,
  `message` mediumtext NOT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `hostname` varchar(50) DEFAULT NULL,
  `uri` mediumtext,
  `refer` varchar(255) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `budgets`
--

CREATE TABLE `budgets` (
  `id` int NOT NULL,
  `application_id` int NOT NULL,
  `year` char(50) DEFAULT NULL,
  `budget_period` varchar(255) DEFAULT NULL,
  `personnel_currency` char(10) DEFAULT NULL,
  `personnel` varchar(11) DEFAULT NULL,
  `transport_currency` char(10) DEFAULT NULL,
  `transport` varchar(11) DEFAULT NULL,
  `field_currency` char(10) DEFAULT NULL,
  `field` varchar(11) DEFAULT NULL,
  `supplies_currency` char(10) DEFAULT NULL,
  `supplies` varchar(11) DEFAULT NULL,
  `pharmacy_currency` char(10) DEFAULT NULL,
  `pharmacy` varchar(11) DEFAULT NULL,
  `travel_currency` char(10) DEFAULT NULL,
  `travel` varchar(11) DEFAULT NULL,
  `regulatory_currency` char(10) DEFAULT NULL,
  `regulatory` varchar(11) DEFAULT NULL,
  `it_currency` char(10) DEFAULT NULL,
  `it` varchar(11) DEFAULT NULL,
  `lab_currency` char(10) DEFAULT NULL,
  `lab` varchar(11) DEFAULT NULL,
  `others_currency` char(10) DEFAULT NULL,
  `others` varchar(11) DEFAULT NULL,
  `kemri_currency` char(10) DEFAULT NULL,
  `kemri` varchar(11) DEFAULT NULL,
  `wrair_currency` char(10) DEFAULT NULL,
  `wrair` varchar(11) DEFAULT NULL,
  `subject_currency` char(10) DEFAULT NULL,
  `subject` varchar(11) DEFAULT NULL,
  `grand_currency` char(10) DEFAULT NULL,
  `grand_total` varchar(11) DEFAULT NULL,
  `study_information` text,
  `personnel_kshs` varchar(11) DEFAULT NULL,
  `transport_kshs` varchar(11) DEFAULT NULL,
  `field_kshs` varchar(11) DEFAULT NULL,
  `supplies_kshs` varchar(11) DEFAULT NULL,
  `pharmacy_kshs` varchar(11) DEFAULT NULL,
  `travel_kshs` varchar(11) DEFAULT NULL,
  `regulatory_kshs` varchar(11) DEFAULT NULL,
  `it_kshs` varchar(11) DEFAULT NULL,
  `lab_kshs` varchar(11) DEFAULT NULL,
  `others_kshs` varchar(11) DEFAULT NULL,
  `grand_total_kshs` varchar(11) DEFAULT NULL,
  `subject_kshs` varchar(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cioms`
--

CREATE TABLE `cioms` (
  `id` int UNSIGNED NOT NULL,
  `application_id` int DEFAULT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `reporter_email` varchar(255) DEFAULT NULL,
  `e2b_content` longtext,
  `e2b_file` varchar(255) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `dirname` varchar(255) DEFAULT NULL,
  `basename` varchar(255) NOT NULL,
  `checksum` varchar(255) NOT NULL,
  `alternative` varchar(50) DEFAULT NULL,
  `group` varchar(255) DEFAULT NULL,
  `description` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int NOT NULL,
  `foreign_key` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `model_id` int DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `sender` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `content` longtext,
  `qrcode` text,
  `review` text,
  `submitted` tinyint DEFAULT '0',
  `message_type` varchar(255) DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `concomittant_drugs`
--

CREATE TABLE `concomittant_drugs` (
  `id` int NOT NULL,
  `sae_id` int DEFAULT NULL,
  `generic_name` varchar(100) DEFAULT NULL,
  `dose` varchar(100) DEFAULT NULL,
  `route_id` int DEFAULT NULL,
  `indication` varchar(255) DEFAULT NULL,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `description` text,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `mobile_number` varchar(255) DEFAULT NULL,
  `telephone_number` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `box_address` varchar(255) DEFAULT NULL,
  `fax_number` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `counties`
--

CREATE TABLE `counties` (
  `id` int NOT NULL,
  `county_name` varchar(50) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `counties`
--

INSERT INTO `counties` (`id`, `county_name`, `created`, `modified`) VALUES
(1, 'Mombasa', '2012-05-31 16:15:11', '2012-07-09 13:06:23'),
(2, 'Kwale', '2012-05-31 16:15:21', '2012-05-31 16:15:21'),
(3, 'Kilifi', '2012-05-31 16:15:49', '2012-05-31 16:15:49'),
(4, 'Tana River', '2012-05-31 16:15:57', '2012-05-31 16:15:57'),
(5, 'Lamu', '2012-05-31 16:16:04', '2012-05-31 16:16:04'),
(6, 'Taita Taveta', '2012-05-31 16:16:22', '2012-05-31 16:16:22'),
(7, 'Garissa', '2012-05-31 16:16:29', '2012-05-31 16:16:29'),
(8, 'Wajir', '2012-06-15 10:22:58', '2012-06-15 10:22:58'),
(9, 'Mandera', '2012-06-15 10:23:07', '2012-06-15 10:23:07'),
(10, 'Marsabit', '2012-06-15 10:23:14', '2012-06-15 10:23:14'),
(11, 'Isiolo', '2012-06-15 10:23:21', '2012-06-15 10:23:21'),
(12, 'Meru', '2012-06-15 10:23:27', '2012-06-15 10:23:27'),
(13, 'Tharaka Nithi', '2012-06-15 10:23:35', '2012-06-15 10:23:35'),
(14, 'Embu', '2012-06-15 10:23:42', '2012-06-15 10:23:42'),
(15, 'Kitui', '2012-06-15 10:23:48', '2012-06-15 10:23:48'),
(16, 'Machakos', '2012-06-15 10:23:55', '2012-06-15 10:23:55'),
(17, 'Makueni', '2012-06-15 10:24:02', '2012-06-15 10:24:02'),
(18, 'Nyandarua', '2012-06-15 10:24:09', '2012-06-15 10:24:09'),
(19, 'Nyeri', '2012-06-15 10:24:16', '2012-06-15 10:24:16'),
(20, 'Kirinyaga', '2012-06-15 10:24:22', '2012-06-15 10:24:22'),
(21, 'Murang\'a', '2012-06-15 10:24:31', '2012-06-15 10:24:31'),
(22, 'Kiambu', '2012-06-15 10:24:37', '2012-06-15 10:24:37'),
(23, 'Turkana', '2012-06-15 10:24:43', '2012-06-15 10:24:43'),
(24, 'West Pokot', '2012-06-15 10:24:52', '2012-06-15 10:24:52'),
(25, 'Samburu', '2012-06-15 10:24:58', '2012-06-15 10:24:58'),
(26, 'Trans Nzoia', '2012-06-15 10:25:05', '2012-06-15 10:25:05'),
(27, 'Uasin Gishu', '2012-06-15 10:25:15', '2012-06-15 10:25:15'),
(28, 'Elgeyo/Marakwet', '2012-06-15 10:25:27', '2012-06-15 10:25:27'),
(29, 'Nandi', '2012-06-15 10:25:33', '2012-06-15 10:25:33'),
(30, 'Baringo', '2012-06-15 10:25:39', '2012-06-15 10:25:39'),
(31, 'Laikipia', '2012-06-15 10:25:46', '2012-06-15 10:25:46'),
(32, 'Nakuru', '2012-06-15 10:25:52', '2012-06-15 10:25:52'),
(33, 'Narok', '2012-06-15 10:26:02', '2012-06-15 10:26:02'),
(34, 'Kajiado', '2012-06-15 10:26:09', '2012-06-15 10:26:09'),
(35, 'Kericho', '2012-06-15 10:26:16', '2012-06-15 10:26:16'),
(36, 'Bomet', '2012-06-15 10:26:23', '2012-06-15 10:26:23'),
(37, 'Kakamega', '2012-06-15 10:26:29', '2012-06-15 10:26:29'),
(38, 'Vihiga', '2012-06-15 10:26:37', '2012-06-15 10:26:37'),
(39, 'Bung\'oma', '2012-06-15 10:26:45', '2012-06-15 10:26:45'),
(40, 'Busia', '2012-06-15 10:26:51', '2012-06-15 10:26:51'),
(41, 'Siaya', '2012-06-15 10:26:56', '2012-06-15 10:26:56'),
(42, 'Kisumu', '2012-06-15 10:27:02', '2012-06-15 10:27:02'),
(43, 'Homa Bay', '2012-06-15 10:27:10', '2012-06-15 10:27:10'),
(44, 'Migori', '2012-06-15 10:27:16', '2012-06-15 10:27:16'),
(45, 'Kisii', '2012-06-15 10:27:25', '2012-06-15 10:27:25'),
(46, 'Nyamira', '2012-06-15 10:27:32', '2012-06-15 10:27:32'),
(47, 'Nairobi City', '2012-06-15 10:27:40', '2012-06-15 10:27:40'),
(48, 'kisii', '2026-06-09 10:02:33', '2026-06-09 10:02:33');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int NOT NULL,
  `code` char(2) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT '',
  `name` tinytext CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `name_fr` tinytext CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `code`, `name`, `name_fr`, `created`, `modified`) VALUES
(1, 'AD', 'Andorra', 'Andorre', NULL, NULL),
(2, 'AE', 'United Arab Emirates', 'Émirats arabes unis', NULL, NULL),
(3, 'AF', 'Afghanistan', 'Afghanistan', NULL, NULL),
(4, 'AG', 'Antigua and Barbuda', 'Antigua-et-Barbuda', NULL, NULL),
(5, 'AI', 'Anguilla', 'Anguilla', NULL, NULL),
(6, 'AL', 'Albania', 'Albanie', NULL, NULL),
(7, 'AM', 'Armenia', 'Arménie', NULL, NULL),
(8, 'AO', 'Angola', 'Angola', NULL, '2012-07-09 14:58:07'),
(9, 'AQ', 'Antarctica', 'Antarctique', NULL, NULL),
(10, 'AR', 'Argentina', 'Argentine', NULL, NULL),
(11, 'AS', 'American Samoa', 'Samoa américaine', NULL, NULL),
(12, 'AT', 'Austria', 'Autriche', NULL, NULL),
(13, 'AU', 'Australia', 'Australie', NULL, NULL),
(14, 'AW', 'Aruba', 'Aruba', NULL, NULL),
(16, 'AZ', 'Azerbaijan', 'Azerbaïdjan', NULL, NULL),
(17, 'BA', 'Bosnia and Herzegovina', 'Bosnie-Herzégovine', NULL, NULL),
(18, 'BB', 'Barbados', 'Barbade', NULL, NULL),
(19, 'BD', 'Bangladesh', 'Bangladesh', NULL, NULL),
(20, 'BE', 'Belgium', 'Belgique', NULL, NULL),
(21, 'BF', 'Burkina Faso', 'Burkina Faso', NULL, NULL),
(22, 'BG', 'Bulgaria', 'Bulgarie', NULL, NULL),
(23, 'BH', 'Bahrain', 'Bahreïn', NULL, NULL),
(24, 'BI', 'Burundi', 'Burundi', NULL, NULL),
(25, 'BJ', 'Benin', 'Bénin', NULL, NULL),
(26, 'BL', 'Saint Barthélemy', 'Saint-Barthélemy', NULL, NULL),
(27, 'BM', 'Bermuda', 'Bermudes', NULL, NULL),
(28, 'BN', 'Brunei Darussalam', 'Brunei Darussalam', NULL, NULL),
(29, 'BO', 'Bolivia', 'Bolivie', NULL, NULL),
(30, 'BQ', 'Caribbean Netherlands ', 'Pays-Bas caribéens', NULL, NULL),
(31, 'BR', 'Brazil', 'Brésil', NULL, NULL),
(32, 'BS', 'Bahamas', 'Bahamas', NULL, NULL),
(33, 'BT', 'Bhutan', 'Bhoutan', NULL, NULL),
(34, 'BV', 'Bouvet Island', 'Île Bouvet', NULL, NULL),
(35, 'BW', 'Botswana', 'Botswana', NULL, NULL),
(36, 'BY', 'Belarus', 'Bélarus', NULL, NULL),
(37, 'BZ', 'Belize', 'Belize', NULL, NULL),
(38, 'CA', 'Canada', 'Canada', NULL, NULL),
(39, 'CC', 'Cocos (Keeling) Islands', 'Îles Cocos (Keeling)', NULL, NULL),
(40, 'CD', 'Congo, Democratic Republic of', 'Congo, République démocratique du', NULL, NULL),
(41, 'CF', 'Central African Republic', 'République centrafricaine', NULL, NULL),
(42, 'CG', 'Congo', 'Congo', NULL, NULL),
(43, 'CH', 'Switzerland', 'Suisse', NULL, NULL),
(44, 'CI', 'Côte D’Ivoire', 'Côte d’Ivoire', NULL, NULL),
(45, 'CK', 'Cook Islands', 'Îles Cook', NULL, NULL),
(46, 'CL', 'Chile', 'Chili', NULL, NULL),
(47, 'CM', 'Cameroon', 'Cameroun', NULL, NULL),
(48, 'CN', 'China', 'Chine', NULL, NULL),
(49, 'CO', 'Colombia', 'Colombie', NULL, NULL),
(50, 'CR', 'Costa Rica', 'Costa Rica', NULL, NULL),
(51, 'CU', 'Cuba', 'Cuba', NULL, NULL),
(52, 'CV', 'Cape Verde', 'Cap-Vert', NULL, NULL),
(53, 'CW', 'Curaçao', 'Curaçao', NULL, NULL),
(54, 'CX', 'Christmas Island', 'Île Christmas', NULL, NULL),
(55, 'CY', 'Cyprus', 'Chypre', NULL, NULL),
(56, 'CZ', 'Czech Republic', 'République tchèque', NULL, NULL),
(57, 'DE', 'Germany', 'Allemagne', NULL, NULL),
(58, 'DJ', 'Djibouti', 'Djibouti', NULL, NULL),
(59, 'DK', 'Denmark', 'Danemark', NULL, NULL),
(60, 'DM', 'Dominica', 'Dominique', NULL, NULL),
(61, 'DO', 'Dominican Republic', 'République dominicaine', NULL, NULL),
(62, 'DZ', 'Algeria', 'Algérie', NULL, NULL),
(63, 'EC', 'Ecuador', 'Équateur', NULL, NULL),
(64, 'EE', 'Estonia', 'Estonie', NULL, NULL),
(65, 'EG', 'Egypt', 'Égypte', NULL, NULL),
(66, 'EH', 'Western Sahara', 'Sahara Occidental', NULL, NULL),
(67, 'ER', 'Eritrea', 'Érythrée', NULL, NULL),
(68, 'ES', 'Spain', 'Espagne', NULL, NULL),
(69, 'ET', 'Ethiopia', 'Éthiopie', NULL, NULL),
(70, 'FI', 'Finland', 'Finlande', NULL, NULL),
(71, 'FJ', 'Fiji', 'Fidji', NULL, NULL),
(72, 'FK', 'Falkland Islands', 'Îles Malouines', NULL, NULL),
(73, 'FM', 'Micronesia, Federated States of', 'Micronésie, États fédérés de', NULL, NULL),
(74, 'FO', 'Faroe Islands', 'Îles Féroé', NULL, NULL),
(75, 'FR', 'France', 'France', NULL, NULL),
(76, 'GA', 'Gabon', 'Gabon', NULL, NULL),
(77, 'GB', 'United Kingdom', 'Royaume-Uni', NULL, NULL),
(78, 'GD', 'Grenada', 'Grenade', NULL, NULL),
(79, 'GE', 'Georgia', 'Géorgie', NULL, NULL),
(80, 'GF', 'French Guiana', 'Guyane française', NULL, NULL),
(81, 'GG', 'Guernsey', 'Guernesey', NULL, NULL),
(82, 'GH', 'Ghana', 'Ghana', NULL, NULL),
(83, 'GI', 'Gibraltar', 'Gibraltar', NULL, NULL),
(84, 'GL', 'Greenland', 'Groenland', NULL, NULL),
(85, 'GM', 'Gambia', 'Gambie', NULL, NULL),
(86, 'GN', 'Guinea', 'Guinée', NULL, NULL),
(87, 'GP', 'Guadeloupe', 'Guadeloupe', NULL, NULL),
(88, 'GQ', 'Equatorial Guinea', 'Guinée équatoriale', NULL, NULL),
(89, 'GR', 'Greece', 'Grèce', NULL, NULL),
(90, 'GS', 'South Georgia and the South Sandwich Islands', 'Géorgie du Sud et les îles Sandwich du Sud', NULL, NULL),
(91, 'GT', 'Guatemala', 'Guatemala', NULL, NULL),
(92, 'GU', 'Guam', 'Guam', NULL, NULL),
(93, 'GW', 'Guinea-Bissau', 'Guinée-Bissau', NULL, NULL),
(94, 'GY', 'Guyana', 'Guyana', NULL, NULL),
(95, 'HK', 'Hong Kong', 'Hong Kong', NULL, NULL),
(96, 'HM', 'Heard and McDonald Islands', 'Îles Heard et McDonald', NULL, NULL),
(97, 'HN', 'Honduras', 'Honduras', NULL, NULL),
(98, 'HR', 'Croatia', 'Croatie', NULL, NULL),
(99, 'HT', 'Haiti', 'Haïti', NULL, NULL),
(100, 'HU', 'Hungary', 'Hongrie', NULL, NULL),
(101, 'ID', 'Indonesia', 'Indonésie', NULL, NULL),
(102, 'IE', 'Ireland', 'Irlande', NULL, NULL),
(103, 'IL', 'Israel', 'Israël', NULL, NULL),
(104, 'IM', 'Isle of Man', 'Île de Man', NULL, NULL),
(105, 'IN', 'India', 'Inde', NULL, NULL),
(106, 'IO', 'British Indian Ocean Territory', 'Territoire britannique de l\'océan Indien', NULL, NULL),
(107, 'IQ', 'Iraq', 'Irak', NULL, NULL),
(108, 'IR', 'Iran', 'Iran', NULL, NULL),
(109, 'IS', 'Iceland', 'Islande', NULL, NULL),
(110, 'IT', 'Italy', 'Italie', NULL, NULL),
(111, 'JE', 'Jersey', 'Jersey', NULL, NULL),
(112, 'JM', 'Jamaica', 'Jamaïque', NULL, NULL),
(113, 'JO', 'Jordan', 'Jordanie', NULL, NULL),
(114, 'JP', 'Japan', 'Japon', NULL, NULL),
(115, 'KE', 'Kenya', 'Kenya', NULL, '2021-07-22 12:06:56'),
(116, 'KG', 'Kyrgyzstan', 'Kirghizistan', NULL, NULL),
(117, 'KH', 'Cambodia', 'Cambodge', NULL, NULL),
(118, 'KI', 'Kiribati', 'Kiribati', NULL, NULL),
(119, 'KM', 'Comoros', 'Comores', NULL, NULL),
(120, 'KN', 'Saint Kitts and Nevis', 'Saint-Kitts-et-Nevis', NULL, NULL),
(121, 'KP', 'North Korea', 'Corée du Nord', NULL, NULL),
(122, 'KR', 'South Korea', 'Corée du Sud', NULL, NULL),
(123, 'KW', 'Kuwait', 'Koweït', NULL, NULL),
(124, 'KY', 'Cayman Islands', 'Îles Caïmans', NULL, NULL),
(125, 'KZ', 'Kazakhstan', 'Kazakhstan', NULL, NULL),
(126, 'LA', 'Lao People’s Democratic Republic', 'Laos', NULL, NULL),
(127, 'LB', 'Lebanon', 'Liban', NULL, NULL),
(128, 'LC', 'Saint Lucia', 'Sainte-Lucie', NULL, NULL),
(129, 'LI', 'Liechtenstein', 'Liechtenstein', NULL, NULL),
(130, 'LK', 'Sri Lanka', 'Sri Lanka', NULL, NULL),
(131, 'LR', 'Liberia', 'Libéria', NULL, NULL),
(132, 'LS', 'Lesotho', 'Lesotho', NULL, NULL),
(133, 'LT', 'Lithuania', 'Lituanie', NULL, NULL),
(134, 'LU', 'Luxembourg', 'Luxembourg', NULL, NULL),
(135, 'LV', 'Latvia', 'Lettonie', NULL, NULL),
(136, 'LY', 'Libya', 'Libye', NULL, NULL),
(137, 'MA', 'Morocco', 'Maroc', NULL, NULL),
(138, 'MC', 'Monaco', 'Monaco', NULL, NULL),
(139, 'MD', 'Moldova', 'Moldavie', NULL, NULL),
(140, 'ME', 'Montenegro', 'Monténégro', NULL, NULL),
(141, 'MF', 'Saint-Martin (France)', 'Saint-Martin (France)', NULL, NULL),
(142, 'MG', 'Madagascar', 'Madagascar', NULL, NULL),
(143, 'MH', 'Marshall Islands', 'Îles Marshall', NULL, NULL),
(144, 'MK', 'Macedonia', 'Macédoine', NULL, NULL),
(145, 'ML', 'Mali', 'Mali', NULL, NULL),
(146, 'MM', 'Myanmar', 'Myanmar', NULL, NULL),
(147, 'MN', 'Mongolia', 'Mongolie', NULL, NULL),
(148, 'MO', 'Macau', 'Macao', NULL, NULL),
(149, 'MP', 'Northern Mariana Islands', 'Mariannes du Nord', NULL, '2012-07-09 14:14:26'),
(150, 'MQ', 'Martinique', 'Martinique', NULL, NULL),
(151, 'MR', 'Mauritania', 'Mauritanie', NULL, NULL),
(152, 'MS', 'Montserrat', 'Montserrat', NULL, NULL),
(153, 'MT', 'Malta', 'Malte', NULL, NULL),
(154, 'MU', 'Mauritius', 'Maurice', NULL, NULL),
(155, 'MV', 'Maldives', 'Maldives', NULL, NULL),
(156, 'MW', 'Malawi', 'Malawi', NULL, NULL),
(157, 'MX', 'Mexico', 'Mexique', NULL, NULL),
(158, 'MY', 'Malaysia', 'Malaisie', NULL, NULL),
(159, 'MZ', 'Mozambique', 'Mozambique', NULL, NULL),
(160, 'NA', 'Namibia', 'Namibie', NULL, NULL),
(161, 'NC', 'New Caledonia', 'Nouvelle-Calédonie', NULL, NULL),
(162, 'NE', 'Niger', 'Niger', NULL, NULL),
(163, 'NF', 'Norfolk Island', 'Île Norfolk', NULL, NULL),
(164, 'NG', 'Nigeria', 'Nigeria', NULL, NULL),
(165, 'NI', 'Nicaragua', 'Nicaragua', NULL, NULL),
(166, 'NL', 'The Netherlands', 'Pays-Bas', NULL, NULL),
(167, 'NO', 'Norway', 'Norvège', NULL, NULL),
(168, 'NP', 'Nepal', 'Népal', NULL, NULL),
(169, 'NR', 'Nauru', 'Nauru', NULL, NULL),
(170, 'NU', 'Niue', 'Niue', NULL, NULL),
(171, 'NZ', 'New Zealand', 'Nouvelle-Zélande', NULL, NULL),
(172, 'OM', 'Oman', 'Oman', NULL, NULL),
(173, 'PA', 'Panama', 'Panama', NULL, NULL),
(174, 'PE', 'Peru', 'Pérou', NULL, NULL),
(175, 'PF', 'French Polynesia', 'Polynésie française', NULL, NULL),
(176, 'PG', 'Papua New Guinea', 'Papouasie-Nouvelle-Guinée', NULL, NULL),
(177, 'PH', 'Philippines', 'Philippines', NULL, NULL),
(178, 'PK', 'Pakistan', 'Pakistan', NULL, NULL),
(179, 'PL', 'Poland', 'Pologne', NULL, NULL),
(180, 'PM', 'St. Pierre and Miquelon', 'Saint-Pierre-et-Miquelon', NULL, NULL),
(181, 'PN', 'Pitcairn', 'Pitcairn', NULL, NULL),
(182, 'PR', 'Puerto Rico', 'Puerto Rico', NULL, NULL),
(183, 'PS', 'Palestinian Territory, Occupied', 'Territoires palestiniens', NULL, NULL),
(184, 'PT', 'Portugal', 'Portugal', NULL, NULL),
(185, 'PW', 'Palau', 'Palau', NULL, NULL),
(186, 'PY', 'Paraguay', 'Paraguay', NULL, NULL),
(187, 'QA', 'Qatar', 'Qatar', NULL, NULL),
(188, 'RE', 'Reunion', 'Réunion', NULL, NULL),
(189, 'RO', 'Romania', 'Roumanie', NULL, NULL),
(190, 'RS', 'Serbia', 'Serbie', NULL, NULL),
(191, 'RU', 'Russian Federation', 'Russie', NULL, NULL),
(192, 'RW', 'Rwanda', 'Rwanda', NULL, NULL),
(193, 'SA', 'Saudi Arabia', 'Arabie saoudite', NULL, NULL),
(194, 'SB', 'Solomon Islands', 'Îles Salomon', NULL, NULL),
(195, 'SC', 'Seychelles', 'Seychelles', NULL, NULL),
(196, 'SD', 'Sudan', 'Soudan', NULL, NULL),
(197, 'SE', 'Sweden', 'Suède', NULL, NULL),
(198, 'SG', 'Singapore', 'Singapour', NULL, NULL),
(199, 'SH', 'Saint Helena', 'Sainte-Hélène', NULL, NULL),
(200, 'SI', 'Slovenia', 'Slovénie', NULL, NULL),
(201, 'SJ', 'Svalbard and Jan Mayen Islands', 'Svalbard et île de Jan Mayen', NULL, NULL),
(202, 'SK', 'Slovakia (Slovak Republic)', 'Slovaquie (République slovaque)', NULL, NULL),
(203, 'SL', 'Sierra Leone', 'Sierra Leone', NULL, NULL),
(204, 'SM', 'San Marino', 'Saint-Marin', NULL, NULL),
(205, 'SN', 'Senegal', 'Sénégal', NULL, NULL),
(206, 'SO', 'Somalia', 'Somalie', NULL, NULL),
(207, 'SR', 'Suriname', 'Suriname', NULL, NULL),
(208, 'SS', 'South Sudan', 'Soudan du Sud', NULL, NULL),
(209, 'ST', 'Sao Tome and Principe', 'Sao Tomé-et-Principe', NULL, NULL),
(210, 'SV', 'El Salvador', 'El Salvador', NULL, NULL),
(211, 'SX', 'Saint-Martin (Pays-Bas)', 'Sint Maarten ', NULL, NULL),
(212, 'SY', 'Syria', 'Syrie', NULL, NULL),
(213, 'SZ', 'Swaziland', 'Swaziland', NULL, NULL),
(214, 'TC', 'Turks and Caicos Islands', 'Îles Turks et Caicos', NULL, NULL),
(215, 'TD', 'Chad', 'Tchad', NULL, NULL),
(216, 'TF', 'French Southern Territories', 'Terres australes françaises', NULL, NULL),
(217, 'TG', 'Togo', 'Togo', NULL, NULL),
(218, 'TH', 'Thailand', 'Thaïlande', NULL, NULL),
(219, 'TJ', 'Tajikistan', 'Tadjikistan', NULL, NULL),
(220, 'TK', 'Tokelau', 'Tokelau', NULL, NULL),
(221, 'TL', 'Timor-Leste', 'Timor-Leste', NULL, NULL),
(222, 'TM', 'Turkmenistan', 'Turkménistan', NULL, NULL),
(223, 'TN', 'Tunisia', 'Tunisie', NULL, NULL),
(224, 'TO', 'Tonga', 'Tonga', NULL, NULL),
(225, 'TR', 'Turkey', 'Turquie', NULL, NULL),
(226, 'TT', 'Trinidad and Tobago', 'Trinité-et-Tobago', NULL, NULL),
(227, 'TV', 'Tuvalu', 'Tuvalu', NULL, NULL),
(228, 'TW', 'Taiwan', 'Taïwan', NULL, NULL),
(229, 'TZ', 'Tanzania', 'Tanzanie', NULL, NULL),
(230, 'UA', 'Ukraine', 'Ukraine', NULL, NULL),
(231, 'UG', 'Uganda', 'Ouganda', NULL, NULL),
(232, 'UM', 'United States Minor Outlying Islands', 'Îles mineures éloignées des États-Unis', NULL, NULL),
(233, 'US', 'United States', 'États-Unis', NULL, NULL),
(234, 'UY', 'Uruguay', 'Uruguay', NULL, NULL),
(235, 'UZ', 'Uzbekistan', 'Ouzbékistan', NULL, NULL),
(236, 'VA', 'Vatican', 'Vatican', NULL, NULL),
(237, 'VC', 'Saint Vincent and the Grenadines', 'Saint-Vincent-et-les-Grenadines', NULL, NULL),
(238, 'VE', 'Venezuela', 'Venezuela', NULL, NULL),
(239, 'VG', 'Virgin Islands (British)', 'Îles Vierges britanniques', NULL, NULL),
(240, 'VI', 'Virgin Islands (U.S.)', 'Îles Vierges américaines', NULL, NULL),
(241, 'VN', 'Vietnam', 'Vietnam', NULL, NULL),
(242, 'VU', 'Vanuatu', 'Vanuatu', NULL, NULL),
(243, 'WF', 'Wallis and Futuna Islands', 'Îles Wallis-et-Futuna', NULL, NULL),
(244, 'WS', 'Samoa', 'Samoa', NULL, NULL),
(245, 'YE', 'Yemen', 'Yémen', NULL, NULL),
(246, 'YT', 'Mayotte', 'Mayotte', NULL, NULL),
(247, 'ZA', 'South Africa', 'Afrique du Sud', NULL, NULL),
(248, 'ZM', 'Zambia', 'Zambie', NULL, NULL),
(249, 'ZW', 'Zimbabwe', 'Zimbabwe', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `deletion_settings`
--

CREATE TABLE `deletion_settings` (
  `id` int NOT NULL,
  `duration_months` int UNSIGNED NOT NULL DEFAULT '3',
  `created_by` int DEFAULT NULL,
  `modified_by` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `deviations`
--

CREATE TABLE `deviations` (
  `id` int NOT NULL,
  `application_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `study_title` text,
  `deviation_type` varchar(255) DEFAULT NULL,
  `deviation_type_dev` varchar(20) DEFAULT NULL,
  `pi_name` varchar(255) DEFAULT NULL,
  `deviation_date` date DEFAULT NULL,
  `participant_number` varchar(255) DEFAULT NULL,
  `treating_physician` varchar(255) DEFAULT NULL,
  `deviation_description` text,
  `deviation_explanation` text,
  `deviation_measures` text,
  `deviation_preclude` text,
  `sponsor_notified` tinyint(1) DEFAULT '0',
  `sponsor_notification_date` varchar(255) DEFAULT NULL,
  `sponsor_explanation` text,
  `study_impact` text,
  `status` varchar(25) NOT NULL DEFAULT 'Unsubmitted',
  `date_submitted` varchar(255) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ercs`
--

CREATE TABLE `ercs` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `accrediation_date` date DEFAULT NULL,
  `chairperson` varchar(255) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `host_institution` varchar(255) DEFAULT NULL,
  `physical_address` varchar(255) DEFAULT NULL,
  `institution_email` varchar(255) DEFAULT NULL,
  `area_accredited` varchar(255) DEFAULT NULL,
  `email_contacts` varchar(255) DEFAULT NULL,
  `description` text,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ercs`
--

INSERT INTO `ercs` (`id`, `name`, `accrediation_date`, `chairperson`, `contact_person`, `host_institution`, `physical_address`, `institution_email`, `area_accredited`, `email_contacts`, `description`, `created`, `modified`) VALUES
(1, 'Africa International University', '2018-02-12', 'Joash W. Mutua', 'Prof. Caleb Kim/ Anne Njoroge', 'Africa International University', 'P.O Box 24686 - 00502, Karen', 'africaninternational.ac.ke', 'Social Science', 'caleb.kim@africainternational.edu, anne.njoroge@africainternational.edu', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'AIC Kijabe Hospital', '2014-01-23', 'Dr. Peter Halestrap', 'Dr. Everlyn Mbugua', 'AIC Kijabe Hospital', 'AIC Kijabe Hospital, Research Office. P.O. Box 20 Kijabe', 'researcher.kh@gmail.com', 'Health science, Biomedical, biological, social sciences and environmental sciences', 'mededdir.kh@kijabe.net', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'Amref Kenya', '2012-03-06', 'Prof. Mohamed Karama', 'Sarah Karanja', 'Amref Kenya', 'Amref Kenya Country Office, next to Wilson Airport', 'esrc.kenya@amref.org', 'Research protocols involving human participants', 'sarah.karanja@amref.org', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'Chuka University', '2012-03-06', 'Prof. A. M. Magana', 'Benjamin Mugumbi Kungu', 'Chuka University', 'P.O. Box 109 - 60400 Chuka', 'info@cuc.ac.ke', 'Biological and Social Sciences', 'mugambikanga@gmail.com', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'Daystar University', '2016-10-18', 'Prof. Abraham K. Waithima', 'Prof. Michael Bowen', 'Daystar University', 'Daystar University Nairobi campus, Valley road', 'duerb@daystar.ac.ke', 'Social Sciences and Environmental Sciences', 'mbowen@daystar.ac.ke', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'Egerton University', '2012-07-02', 'Prof. Julius Kipkemboi', 'Prof. Julius Kipkemboi', 'Egerton University', 'Egerton University P.O. Box 536-20115 Egerton', 'dvcre@egerton.ac.ke', 'Research protocols involving human participants', 'director_research@egerton.ac.ke', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 'Gertrude Children\'s Hospital Ethical Review Committee', '2012-04-20', 'Dr. Vankwa Indeche', 'Dr. Thomas Ngwiri', 'Getrudes Childrens Home', 'Getrudes Childrens Hospital. Muthaiga Road. Nairobi, Kenya', 'ethics@gerties.org', 'Research protocols involving human participants', 'tngwiri@gerties.org', '', '0000-00-00 00:00:00', '2023-08-28 14:58:11'),
(8, 'Great Lakes University', '2014-04-23', 'Dr. Jane Muuma', 'Evalyne Aseyo', 'Great Lakes University', 'Great Lakes University of Kisumu, Mwani Road. P.O. Box 224 - 40100, Kisumu', 'vcoffice@gluk.ac.ke', 'Public Health', 'glukresearchcenter@gluk.ac.ke', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 'Institute of Primate Research (IPR)', '2012-04-20', 'Dr. Idle Farah', 'Dr. Ngalla Jillani', 'Institute of Primate Research (IPR)', 'End of Karen Road, Oloolua Forest, Karen. P.O. Box 24481 - 00502 Karen', 'ircsecretary@primatesresearch.org', 'Research protocols involving animals', 'ircsecretary@primatesresearch.org', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 'International Centre for Insect Physiology and Ecology (ICIPE)', '2012-03-05', 'Dr. Richard Mukabana', 'Ms. Susan Kariuki', 'International Centre for Insect Physiology and Ecology (ICIPE)', 'Off Thika Road, Duduville Kasarani', 'icipe@icipe.org', 'Biological and Environmental sciences', 'skariuki@icipe.org', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 'International Livestock Research Institute (ILRI)', '2013-06-07', 'Dr. Silvia Alonso', 'Ephy Khaemba', 'International Livestock Research Institute (ILRI)', 'Old Naivasha Road - Uthiru', 'ILRIResearchcompliance@cgiar.org', 'Health sciences', 'e.khaemba@cgiar.org', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 'Jaramogi Oginga Odinga Teaching and Referral Hospital (JOOTRH)', '2006-07-13', 'Dr. Allan Godfrey Otieno', 'Wilbroda Makunda', 'Jaramogi Oginga Odinga Teaching and Referral Hospital (JOOTRH)', 'Jaramogi Oginga Odinga Teaching and Referral Hospital P.O. Box 849 Kisumu', 'medsuptnpgh@yahoo.com', 'Health sciences', 'wilbrodanancy@yahoo.com, gaotieno@gmail.com', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, 'Jaramogi Oginga Odinga University of Science and Technology', '2017-05-09', 'Prof. Francis Angawa', 'Prof. Benson B.A. Estambale', 'Jaramogi Oginga Odinga University of Science and Technology', 'Jaramogi Oginga Odinga University of Science and Technology, Main campus Bondo - Usenge road', 'erc@jooust.ac.ke', 'Biomedical Sciences', 'beestambale@jooust.ac.ke', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, 'Kenya Medical Research Institute', '2012-01-26', 'Mr. Ambrose Rachier', 'Dr. Mercy Karimi', 'Kenya Medical Research Institute', 'Off Mbagathi Road', 'seru@kemri.org', 'Research protocols involving human participants', 'mnjeru@kemri.org', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, 'Kenya Methodist University', '2012-02-01', 'Dr. Alex Wamachi', 'Dr. Alex Wamachi', 'Kenya Methodist University', 'School of Medicine & Health Sciences, Kenya Methodist University - main campus - Meru. P.O. Box 267 - 60200 Meru', 'info@kemu.ac.ke', 'Research protocols involving human participants', 'mutungialice@yahoo.com', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 'Kenyatta National Hospital - University of Nairobi', '2012-02-06', 'Prof. A. N. Guantai', 'Prof. M.L. Chindia', 'Kenyatta National Hospital - University of Nairobi', 'P.O. BOX 20723 - 00202, Off Ngong Road. Nairobi, Kenya', 'uonknherc@uonbi.ac.ke', 'Human Subjects', 'markchindia@gmail.com', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, 'Kenyatta University', '2012-01-30', 'Prof. Judith Kimiywe', 'Elizabeth Karanja', 'Kenyatta University', 'Kenyatta University Along Thika Road superhighway. Old Library building Rooms 23, 24,25', 'chairman.kuerc@ku.ac.ke', 'Human Subjects', 'secretary.kuerc@ku.ac.ke', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, 'Maseno University', '2012-12-04', 'Prof. Philip O.Owour', 'Samwel Bonuke Anyona', 'Maseno University', 'Maseno University - Main Campus. International Linkages Building. Along Kisumu -Busia Road', 'muerc-secretariate@maseno.ac.ke', 'Public health and social sciences', 'sanyona@maseno.ac.ke', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, 'Masinde Muliro University of Science & Technology', '2013-06-07', 'Dr. Gordon Nguka', 'Nelly Jelimo', 'Masinde Muliro University of Science & Technology', 'Kakamega Town Western Kenya along Kakamega - Webuye Road', 'vc@mmust.ac.ke. info@mmust.ac.ke', 'Health sciences', 'njelimo@mmust.ac.ke', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 'Moi Teaching and Referral Hospital/Moi University', '2012-03-06', 'Prof. Edwin O. Were', 'Ms. Catherine Okwiri', 'Moi Teaching and Referral Hospital', 'Moi Teaching and Referral Hospital building, 2nd floor - Door No. 219, Nandi road', 'irec@mtrh.or.ke', 'Biomedical and human participants', 'irecoffice@gmail.com, cokwiri@gmail.com', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 'Mount Kenya University', '2013-06-07', 'Prof. Francis Muregi', 'Penina Njoki Muchira', 'Mount Kenya University', 'Mt. Kenya University P.O. Box 342 - 00100 Thika', 'info@mku.ac.ke', 'Health sciences', 'mercy.njoki@yahoo.com', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 'Pwani University', '2012-04-23', 'Dr. Thomas Rewe', 'James Biriah Ndiso', 'Pwani University College', 'Pwani University College. P.O. Box 195 - 80108, Kilifi P.O. Box 195 - 80108, Kilifi', 'infor@pwaniuniversity.ac.ke', 'Biomedical, Physical, Social sciences and Environment science', 'j.ndiso@pwaniuniversity.ac.ke', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, 'Strathmore University', '2014-04-23', 'Fred Were', 'Dr. Florence Oloo', 'Strathmore University', 'Strathmore University, Ole sangale Road, P.O. Box 59867 - 00200. Nairobi -Kenya', 'foloo@strathmore.edu', 'Social sciences', 'foloo@strathmore.edu', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, 'The Aga Khan University Hospital', '2012-03-06', 'Dr. Amyn Lakhani', 'Ms. Kamanda Nancy', 'The Aga Khan University Hospital', 'Aga Khan University Hospital, 3rd Parklands Avenue, East Tower Block, 7th floor.', 'medicalcollege.enquiries@aku.edu', 'Research protocols involving human participants', 'kamanda.ciru@aku.edu, research.supportea@aku.edu', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, 'The Nairobi Hospital', '2014-04-23', 'Dr. Max O. Okonji', 'Dr. Joan Osoro', 'The Nairobi Hospital', 'Argwing Kodhek Road. The Nairobi Hospital', 'hosp@nbihosp.org', 'Research protocols involving human participants', 'osorombui@nbihosp.org', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, 'United States International University', '2016-11-21', 'Carol Watson', 'Prof. Amos Njuguna', 'United States International University', 'Kasarani area off Thika road', 'irb@usiu.ac.ke', 'Social sciences and Environment science', 'amnjuguna@usiu.ac.ke', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, 'University of Eastern Africa Baraton', '2013-06-07', 'Ms. Jackie K. Obey', 'Elizabeth Rolee', 'University of Eastern Africa Baraton', 'University of Eastern Africa, Baraton. Off Eldoret - Kapsabet Road. P.O. Box 2500, Eldoret', 'recbaraton@ueab.ac.ke', 'Health sciences', 'ueabrec@gmail.com, rolee@ueab.ac.ke', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `ethical_committees`
--

CREATE TABLE `ethical_committees` (
  `id` int NOT NULL,
  `application_id` int DEFAULT NULL,
  `ethical_committee` varchar(255) DEFAULT NULL,
  `submission_date` date DEFAULT NULL,
  `erc_number` varchar(55) DEFAULT NULL,
  `initial_approval` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `id` int NOT NULL,
  `application_id` int DEFAULT NULL,
  `central_organisation` varchar(255) DEFAULT NULL,
  `central_name_contact_person` varchar(255) DEFAULT NULL,
  `central_address` varchar(255) DEFAULT NULL,
  `central_telephone_number` varchar(255) DEFAULT NULL,
  `central_duties_subcontracted` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `foreign_key` int DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `feedback` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `redir` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`, `redir`, `created`, `modified`) VALUES
(1, 'PPB Admins', 'Highest user with rights to the entier system', 'admin', '2012-10-05 20:01:19', '2015-03-12 11:54:50'),
(2, 'PPB Managers', NULL, 'manager', '2012-10-05 20:01:36', '2012-10-05 20:02:58'),
(3, 'ECCT Reviewers', NULL, 'reviewer', '2012-10-05 20:02:09', '2012-10-05 20:02:09'),
(4, 'Partners', 'This role will belong to PPB partners like KEMRI etc. They will have access to creating existing reports.', 'partner', '2012-10-05 20:02:23', '2013-01-12 12:25:24'),
(5, 'Principal Investigators', 'Also called applicants, these are the end users who register in the system and create applications.', 'applicant', '2012-10-05 20:02:39', '2013-01-12 12:20:03'),
(6, 'GCP Inspectors', 'GCP Inspectors', 'inspector', '2020-01-26 22:19:03', '2020-01-26 22:19:19'),
(7, 'Monitors', 'Monitors related to specific user', 'monitor', '2020-02-11 21:13:36', '2020-02-11 21:13:36'),
(8, 'Outsource', 'List of users for outsourced services', 'outsource', '2020-02-11 21:13:36', '2020-02-11 21:13:36'),
(9, 'Internal Reviewers', 'Shows a list of PPB\'s Internal \r\nReviewers', 'internalreviewer', '2026-02-04 20:02:09', '2026-02-04 11:58:20');

-- --------------------------------------------------------

--
-- Table structure for table `information_placebos`
--

CREATE TABLE `information_placebos` (
  `id` int NOT NULL,
  `application_id` int DEFAULT NULL,
  `placebo_number` varchar(255) DEFAULT NULL,
  `placebo_pharmaceutical_form` varchar(255) DEFAULT NULL,
  `placebo_route_of_administration` varchar(255) DEFAULT NULL,
  `placebo_IMP_number` varchar(255) DEFAULT NULL,
  `placebo_composition` varchar(255) DEFAULT NULL,
  `placebo_indentical` varchar(255) DEFAULT NULL,
  `placebo_major_ingredients` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `investigators`
--

CREATE TABLE `investigators` (
  `id` int NOT NULL,
  `application_id` int DEFAULT NULL,
  `given_name` varchar(255) DEFAULT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `family_name` varchar(255) DEFAULT NULL,
  `qualification` varchar(255) DEFAULT NULL,
  `professional_address` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `investigator_contacts`
--

CREATE TABLE `investigator_contacts` (
  `id` int NOT NULL,
  `application_id` int DEFAULT NULL,
  `given_name` varchar(100) DEFAULT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `family_name` varchar(100) DEFAULT NULL,
  `qualification` varchar(255) DEFAULT NULL,
  `professional_address` varchar(255) DEFAULT NULL,
  `telephone` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `investigator_role` varchar(50) DEFAULT 'principal',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int NOT NULL,
  `application_id` int DEFAULT NULL,
  `approval_no` varchar(255) DEFAULT NULL,
  `content` mediumtext,
  `qrcode` text,
  `approver` varchar(255) DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `deleted_date` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `manufacturers`
--

CREATE TABLE `manufacturers` (
  `id` int NOT NULL,
  `application_id` int DEFAULT NULL,
  `manufacturer_name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `manufacturer_country` varchar(55) DEFAULT NULL,
  `manufacturer_phone` varchar(100) DEFAULT NULL,
  `manufacturer_email` varchar(100) DEFAULT NULL,
  `manufacturing_activities` varchar(255) DEFAULT NULL,
  `other_specify` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `meeting_dates`
--

CREATE TABLE `meeting_dates` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `email` varchar(100) NOT NULL,
  `proposed_date1` datetime DEFAULT NULL,
  `proposed_date2` datetime DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `disease_background` text,
  `product_background` text,
  `quality_development` text,
  `non_clinical_development` text,
  `clinical_development` text,
  `regulatory_status` text,
  `advice_rationale` text,
  `proposed_questions` text,
  `approved` tinyint NOT NULL DEFAULT '0',
  `final_decision` text,
  `deleted` tinyint(1) DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int NOT NULL,
  `name` char(50) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `content` text,
  `type` char(30) DEFAULT NULL,
  `style` varchar(250) DEFAULT NULL,
  `description` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `name`, `subject`, `content`, `type`, `style`, `description`, `created`, `modified`) VALUES
(1, 'manager_new_application', NULL, 'A new application has been submitted. Click here <a href=\":protocol_link\"> :protocol_no </a> to assign at least three reviewers.\r\n', 'notification', 'success', 'This Notification is sent to PPB Managers whenever a new application is submitted by an applicant.', '2013-01-05 12:56:49', '2013-01-19 16:00:40'),
(2, 'reviewer_new_application', NULL, '<p>\r\n	Click here <a href=\":protocol_link\">:protocol_no</a> to declare conflict of interest</p>\r\n', 'notification', 'success', 'This notification is sent to PPB reviewers selected by the PPB manager to review an application.', '2013-01-05 16:04:29', '2013-01-12 14:02:12'),
(3, 'manager_reviewer_response', NULL, '<i class=\"icon-star-empty\"></i> :user has responded to the request to review ', 'notification', 'info', NULL, '2013-01-05 19:46:07', '2013-01-10 18:26:30'),
(4, 'registration_email_subject', NULL, 'Thank you for registering!', 'email', 'info', NULL, '2013-01-08 13:53:16', '2013-01-08 13:53:16'),
(5, 'manager_comment_applicant', NULL, '<i class=\"icon-star-empty\"></i> Please read the PPB review comment for  <i class=\'icon-hand-right\'></i> <a href=\":protocol_link\">:protocol_no</a>', 'notification', 'warning', NULL, '2013-01-09 15:11:32', '2013-01-10 19:35:13'),
(6, 'registration_email', NULL, '<p>\r\n	Dear <strong>:name</strong>,</p>\r\n<p>\r\n	You are getting this email because you have registered as a new user in the <a href=\":full_base_url\">:ppb_ctr</a> Your username is <strong>:username</strong>. To activate your account, please click on the link below and then proceed to login</p>\r\n<p>\r\n	<a href=\":activation_link\">ACTIVATE</a></p>\r\n<p>\r\n	<em>if you did not register, you can safely ignore this email!</em></p>\r\n', 'email', 'success', 'Applicant Registration Email', '2013-01-10 14:44:57', '2013-02-22 16:49:16'),
(7, 'manager_new_application_subject', NULL, '<p>New Application</p>\r\n', 'notification', 'success', '', '2013-01-10 17:35:11', '2013-02-22 15:51:06'),
(8, 'reviewer_new_application_subject', NULL, '<i class=\"icon-star-empty\"></i>    Review Protocol', 'notification', 'info', NULL, '2013-01-10 17:41:02', '2013-01-10 17:49:27'),
(9, 'new_reviewer_comment', NULL, '<i class=\"icon-star-empty\"></i> :user has submitted a review comment on ', 'notification', 'warning', NULL, '2013-01-10 19:00:42', '2013-01-10 19:01:05'),
(10, 'approve_message_subject', NULL, '<p>\r\n	:protocol_no has been :approved</p>\r\n', 'notification email', 'success', 'This messages is sent as a notification and an email to the principal investigator (applicant), managers and assigned protocol reviewers.', '2013-01-10 20:50:24', '2013-01-12 14:03:32'),
(11, 'applicant_approve_message', NULL, 'The application <a href=\":protocol_link\">:protocol_no</a> has been :approved!!', NULL, 'info', NULL, '2013-01-10 21:00:00', '2013-01-10 21:19:07'),
(12, 'reviewers_approve_message', NULL, 'The application <a href=\":protocol_link\">:protocol_no</a> has been :approved!! Thank you for your efforts in reviewing the protocol.', NULL, 'info', NULL, '2013-01-10 21:00:55', '2013-01-10 21:19:21'),
(13, 'managers_approve_message', '', '<p>\r\n	The application <a href=\":protocol_link\">:protocol_no</a> has been :approved by :name</p>\r\n', '', 'info', '', '2013-01-10 21:02:07', '2019-08-12 08:58:12'),
(14, 'applicant_new_amendment', NULL, '<p><i class=\"icon-star-empty\"></i> You have an active <a href=\":amendment_link\">amendment</a> for <a href=\":protocol_link\">:protocol_no</a>. Please submit it when you are done.</p>\r\n', 'notification', 'info', 'Notification to applicant when a new amendment is created.', '2013-01-19 16:00:24', '2013-01-19 16:56:00'),
(15, 'submitted_amendment_manager', NULL, '<p>\r\n	<a href=\":protocol_link\">:protocol_no</a> has been amended. Click on the protocol no to view the amendments.</p>', 'notification', 'info', 'A notification sent to the managers when a new amendment has been submitted.', '2013-01-19 16:05:13', '2013-01-19 16:05:13'),
(16, 'applicant_submit_email', NULL, '<p>\r\n	Dear <strong>:name</strong>,</p>\r\n<p>\r\n	Your report has been received at the Pharmacy and Poisons Board and will be reviewed.</p>\r\n<p>\r\n	The unique protocol number for the application is <strong>:protocol_no</strong>. You will get notifications concerning the protocol in your dashboard page (page after successful login).</p>\r\n', 'email', 'success', 'Email sent to applicant after successfully submitting an application.', '2013-02-22 16:22:35', '2013-02-22 16:22:35'),
(17, 'applicant_submit_email_subject', NULL, '<p>\r\n	Submission of Protocol <strong>:protocol_no</strong></p>\r\n', 'email', 'info', 'Email subject sent to applicant after successsful submission of application.', '2013-02-22 16:27:28', '2013-02-22 16:27:28'),
(18, 'registration_welcome_subject', NULL, '<p>\r\n	<span style=\"color: rgb(51, 51, 51); font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 20px; background-color: rgb(249, 249, 249);\">Welcome&nbsp;</span><strong style=\"color: rgb(51, 51, 51); font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 20px; background-color: rgb(249, 249, 249);\">:username</strong><span style=\"color: rgb(51, 51, 51); font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 20px; background-color: rgb(249, 249, 249);\">&nbsp;to the PPB Online Clinical Trials</span></p>\r\n', 'notification', 'info', 'after registration welcome subject', '2013-02-26 18:58:56', '2013-02-26 18:58:56'),
(19, 'registration_welcome_message', NULL, '<p>\r\n	<span style=\"color: rgb(51, 51, 51); font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 20px;\">You will get notifications regarding the statuses of the trials in this panel!</span></p>\r\n', 'notification', 'success', 'message after registration', '2013-02-26 19:00:39', '2013-02-26 19:00:39'),
(20, 'forgot_email_subject', NULL, 'CTR Change of password request\r\n', 'email', 'error', 'User forgot password email', '2014-05-31 14:40:17', '2014-05-31 14:40:17'),
(21, 'forgot_email', NULL, '<p>\r\n	Dear <strong>:name</strong>,</p>\r\n<p>\r\n	You are getting this email because a request has been made to reset your password at <a href=\":full_base_url\">:ppb_ctr</a>. Your username is <strong>:username</strong>.</p>\r\n<p>\r\n	You may safely <strong>IGNORE</strong> this email if you did not request for a change of password.</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	To Reset your password, click on the link below</p>\r\n<p>\r\n	<a href=\":reset_link\">RESET</a></p>\r\n<p>\r\n	If you click on the link, you new password will be <em>:password</em></p>\r\n', 'email', 'error', 'Email content to reset password.', '2014-05-31 14:53:19', '2014-05-31 16:19:12'),
(22, 'manager_new_annual_approval_subject', NULL, 'Annual Approval Document Submitted', 'notification email', 'success', 'A manager will get an email and a notification whenever a new annual approval is submitted.', '2014-05-31 17:00:57', '2014-05-31 17:00:57'),
(23, 'manager_new_annual_approval', NULL, '<p>\r\n	A new annual approval document has been uploaded. Click here <a href=\":protocol_link\"> :protocol_no </a> to view attachments.</p>', 'notification email', 'success', 'Managers will get email and notifications whenever annual aproval documents are submitted', '2014-05-31 17:03:10', '2014-05-31 17:03:10'),
(24, 'manager_new_final_report_subject', NULL, 'Final Report Document Submitted', 'notification email', 'success', 'A manager will get an email and a notification whenever a new final report is submitted.', '2015-01-31 17:00:57', '2015-01-31 17:00:57'),
(25, 'manager_new_final_report', NULL, '<p>\r\n    A new final report document has been uploaded. Click here <a href=\":protocol_link\"> :protocol_no </a> to view attachments.</p>', 'notification email', 'success', 'Managers will get email and notifications whenever final report documents are submitted', '2015-01-31 17:03:10', '2015-01-31 17:03:10'),
(26, 'inspector_new_inspection', 'New Site Inspection for :protocol_no', '<p>\r\n	A new site inspection for :protocol_no has been created.</p>\r\n', 'notification email', 'info', 'Email sent to inspectors after creating a new site inspection.', '2019-05-19 20:09:01', '2019-05-29 16:12:44'),
(27, 'applicant_sae_submit', ':reference_no for :protocol_no submitted for review', '<p>\r\n	Dear :name,</p>\r\n<p>\r\n	New <strong>:reference_link</strong> for <strong>:protocol_link</strong> has been submitted for review to PPB on <strong>:modified</strong>.</p>\r\n<p>\r\n	Regards,</p>\r\n<p>\r\n	PPB</p>\r\n', 'notification email', 'success', 'Notification/Email sent to applicant and regulators when new SAE is submitted.', '2019-05-25 09:59:33', '2019-05-29 16:13:04'),
(28, 'manager_sae_feedback', 'PPB Feedback for :reference_no', '<p>\r\n	Dear :name,</p>\r\n<p>\r\n	Please review feedback from PPB for :reference_link</p>\r\n<p>\r\n	<u><strong>Feedback</strong></u></p>\r\n<p>\r\n	<strong>Subject</strong>: :comment_subject</p>\r\n<p>\r\n	<strong>Content</strong></p>\r\n<p>\r\n	:comment_content</p>\r\n<p>\r\n	Regards,</p>\r\n<p>\r\n	PPB</p>\r\n', 'notification email', 'info', 'Notification and Email sent to applicant and manager when feedback sent.', '2019-05-25 17:36:09', '2019-05-29 16:12:29'),
(29, 'manager_si_feedback', 'PPB Site Inspection Feedback for :reference_no', '<p>\r\n	Dear :name,</p>\r\n<p>\r\n	Please review<strong> site inspection</strong> feedback for :reference_link</p>\r\n<p>\r\n	<u><strong>Feedback</strong></u></p>\r\n<p>\r\n	<strong>Subject</strong>: :comment_subject</p>\r\n<p>\r\n	<strong>Content</strong></p>\r\n<p>\r\n	:comment_content</p>\r\n<p>\r\n	Regards,</p>\r\n<p>\r\n	PPB</p>\r\n', 'notification email', 'info', 'Notification and Email sent to applicant and manager when feedback sent.', '2019-05-26 22:13:23', '2019-05-29 16:12:01'),
(30, 'manager_send_summary', 'PPB Site Inspection Summary for :protocol_no', '<p>\r\n	Dear :name,</p>\r\n<p>\r\n	Please review feedback from PPB for :reference_link</p>\r\n<p>\r\n	<u><strong>Feedback</strong></u></p>\r\n<p>\r\n	<strong>Outcome</strong>: :outcome</p>\r\n<p>\r\n	<strong>Conclusion: </strong>:conclusion</p>\r\n<p>\r\n	Summary Report</p>\r\n<p>\r\n	:summary_report</p>\r\n<p>\r\n	Regards,</p>\r\n<p>\r\n	PPB</p>\r\n', 'notification email', 'error', 'Notification and email sent to reviewers and PI when feedback shared.', '2019-05-28 22:12:13', '2019-05-29 16:11:42'),
(31, 'annual_approval_letter', 'Annual Approval letter generated for :protocol_no', '<p>\r\n	Dear :name,</p>\r\n<p>\r\n	<u><strong>REF: :protocol_link approved for :approval_date</strong></u></p>\r\n<p>\r\n	New Annual pproval <strong>:approval_no</strong> dated <strong>:approval_date</strong> has been generated for <strong>:protocol_link</strong>. Expiry date :expiry_date</p>\r\n<p>\r\n	Regards,</p>\r\n<p>\r\n	PPB</p>\r\n', 'notification email', 'success', 'Notification and Email sent to applicant and managers after successfull annual approval', '2019-08-11 19:57:58', '2025-02-21 12:00:27'),
(32, 'overdue_approval_letter', 'Overdue annual approval for :protocol_no', '<p>\r\n	Dear :name,</p>\r\n<p>\r\n	<u><strong>REF: OVERDUE ANNUAL APPROVAL FOR :protocol_no</strong></u></p>\r\n<p>\r\n	The annual approval for :protocol_link was due on :expiry_date. Kindly submit all the required documents.</p>\r\n<p>\r\n	The Pharmacy and Poisons Board,</p>\r\n<p>\r\n	Kenya</p>\r\n', 'notification email', 'error', 'Notification sent to applicants and managers when application is overdue', '2019-08-12 08:26:59', '2019-08-12 08:52:08'),
(33, 'reminder_approval_letter', 'Annual approval reminder for :protocol_no', '<p>\r\n	Dear Sir/Madam,</p>\r\n<p>\r\n	<u><strong>REF: REMINDER TO SUBMIT ANNUAL APPROVAL FOR :protocol_no </strong></u></p>\r\n<p>\r\n	Your annual approval for :protocol_no is due before :expiry_date. Please submit all the required documents.</p>\r\n<p>\r\n	Pharmacy and Poisons Board,</p>\r\n<p>\r\n	Kenya</p>\r\n', 'notification email', 'warning', 'Notification and email sent to managers and applicants when the annual approval is approaching', '2019-08-12 08:29:31', '2019-08-15 09:51:51'),
(34, 'manager_approve_letter', 'Approval letter generated for :protocol_no', '<p>\r\n	Dear :name,</p>\r\n<p>\r\n	<u><strong>REF: :protocol_link awaiting approval</strong></u></p>\r\n<p>\r\n	New Approval <strong>:approval_no</strong> dated <strong>:approval_date</strong> has been generated and awaits review.</p>\r\n<p>\r\n	Regards,</p>\r\n<p>\r\n	PPB</p>\r\n', 'notification email', 'warning', 'Notification and Email sent to managers after successfull annual approval', '2019-08-11 19:57:58', '2019-08-14 22:10:42'),
(35, 'manager_dev_feedback', 'Protocol deviation for :reference_no', '<p>\n	Dear :name,</p>\n<p>\n	Please review<strong> protocol deviation</strong> for :reference_link</p>\n<p>\n	<u><strong>Feedback (:reference_no)</strong></u></p>\n<p>\n	<strong>Subject</strong>: :comment_subject</p>\n<p>\n	<strong>Content</strong></p>\n<p>\n	:comment_content</p>\n<p>\n	Regards,</p>\n<p>\n	PPB</p>\n', 'notification email', 'warning', 'Notification and email sent when feedback is shared', '2019-08-18 15:29:52', '2019-08-23 22:20:18'),
(36, 'internal_review_comment', 'Review comment for :protocol_no', '<p>\n	<u><strong>Internal Review Comment (:protocol_no)</strong></u></p>\n<p>\n	<strong>Subject</strong>: :comment_subject</p>\n<p>\n	<strong>Content</strong></p>\n<p>\n	:comment_content</p>\n<p>\n	:sender</p>\n<p>\n	Regards,</p>\n<p>\n	PPB</p>\n', 'notification email', 'warning', 'Notification and email sent when review done.', '2019-09-16 14:49:11', '2019-09-16 14:49:11'),
(37, 'applicant_meeting_date_submit', 'Request for pre-submission meeting on :proposed_date1', '<p>\r\n	New request for pre-submission meeting on <strong>:proposed_date1</strong> or <strong>:proposed_date2 </strong>has been submitted by <strong>:email</strong>.</p>\r\n<p>\r\n	:reference_link</p>\r\n', 'notification email', 'success', 'notification sent to managers and applicants when new pre-submission meeting date is suggested.', '2019-12-02 20:47:22', '2019-12-02 22:08:55'),
(38, 'manager_meeting_date_feedback', 'Pre-submission meeting on :proposed_date1', '<p>\r\n	Dear :name,</p>\r\n<p>\r\n	Feedback on proposed meeting date :proposed_date1 or proposed_date2</p>\r\n<p>\r\n	<u><strong>Feedback</strong></u></p>\r\n<p>\r\n	<strong>Subject</strong>: :comment_subject</p>\r\n<p>\r\n	<strong>Content</strong></p>\r\n<p>\r\n	:comment_content</p>\r\n<p>\r\n	Link:</p>\r\n<p>\r\n	:reference_link</p>\r\n<p>\r\n	Regards,</p>\r\n<p>\r\n	PPB</p>\r\n', 'notification email', 'info', 'Email and notification sent during proposed meeting date discussion.', '2019-12-02 21:43:31', '2019-12-02 21:46:08'),
(39, 'screening_feedback', 'Protocol screening feedback', '<p>\r\n	Dear :name,</p>\r\n<p>\r\n	<u><strong>Screening Feedback</strong></u></p>\r\n<p>\r\n	<strong>Subject</strong>: :comment_subject</p>\r\n<p>\r\n	<strong>Content</strong></p>\r\n<p>\r\n	:comment_content</p>\r\n<p>\r\n	Link:</p>\r\n<p>\r\n	:reference_link</p>\r\n<p>\r\n	Regards,</p>\r\n<p>\r\n	PPB</p>\r\n', 'notification email', 'info', 'Email and notification sent during screening for completeness stage', '2020-01-12 14:48:23', '2020-01-12 14:48:23'),
(40, 'review_response', 'Review feedback', '<p>\r\n	Dear :name,</p>\r\n<p>\r\n	<u><strong>Review Feedback</strong></u></p>\r\n<p>\r\n	<strong>Subject</strong>: :comment_subject</p>\r\n<p>\r\n	<strong>Content</strong></p>\r\n<p>\r\n	:comment_content</p>\r\n<p>\r\n	Link:</p>\r\n<p>\r\n	:reference_link</p>\r\n<p>\r\n	Regards,</p>\r\n<p>\r\n	PPB</p>\r\n', 'notification email', 'warning', 'Email and notification sent during review process.', '2020-01-14 15:58:51', '2020-01-14 15:58:51'),
(41, 'contact_feedback', 'Feedback: :subject', '<p>\r\n	Dear :name,</p>\r\n<p>\r\n	Feedback from CTR</p>\r\n<p>\r\n	Subject: :subject</p>\r\n<p>\r\n	Feedback</p>\r\n<p>\r\n	:feedback</p>\r\n<p>\r\n	&nbsp;</p>\r\n', 'notification email', 'success', 'notification and email sent from feedback page', '2020-03-29 18:13:57', '2020-03-29 18:13:57'),
(42, 'annual_letter_comment', 'Annual Letter comment for :protocol_no', '<p>\n	<u><strong>Annual Letter Comment   \n (:protocol_no)</strong></u></p>\n<p>\n	<strong>Subject</strong>: :comment_subject</p>\n<p>\n	<strong>Content</strong></p>\n<p>\n	:comment_content</p>\n<p>\n	:sender</p>\n<p>\n	Regards,</p>\n<p>\n	PPB</p>\n', 'notification email', 'warning', 'Notification and email sent when review done.', '2019-09-16 14:49:11', '2019-09-16 14:49:11'),
(43, 'inspector_new_application_subject', NULL, '<i class=\"icon-star-empty\"></i>    Site Inspection for Protocol', 'notification', 'info', NULL, '2013-01-10 17:41:02', '2013-01-10 17:49:27'),
(44, 'manager_approve_amendment_letter', 'Approval letter generated for :protocol_no', '<p>\r\n	Dear :name,</p>\r\n<p>\r\n	<u><strong>REF: :protocol_link awaiting approval</strong></u></p>\r\n<p>\r\n	New Approval <strong>:approval_no</strong> dated <strong>:approval_date</strong> has been generated and awaits review.</p>\r\n<p>\r\n	Regards,</p>\r\n<p>\r\n	PPB</p>\r\n', 'notification email', 'warning', 'Notification and Email sent to managers after successfull annual approval', '2019-08-11 19:57:58', '2019-08-14 22:10:42'),
(45, 'amendment_submission', 'Amendment submission for :protocol_no', '<p>\r\n	Dear :name,</p>\r\n<p>\r\n	<u><strong>REF: Amendment Submission for  :protocol_no awaiting approval</strong></u></p>\r\n<p>New Amendment Submission has been submitted and awaits review.</p>\r\n\r\n<p>Access link :protocol_link</p>\r\n<p>\r\n	Regards,</p>\r\n<p>\r\n	PPB</p>\r\n', 'notification email', 'warning', 'Notification and Email sent to managers after successfull amendment submission', '2019-08-11 19:57:58', '2019-08-14 22:10:42'),
(46, 'amendment_approval_letter', 'Amendment approval letter generated for :protocol_no', '<p>\r\n	Dear :name,</p>\r\n<p>\r\n	<u><strong>REF: :protocol_no approved for :approval_date</strong></u></p>\r\n<p>\r\n	New Amendment Approval Letter  dated <strong>:approval_date</strong> has been generated for <strong>:protocol_link</strong>. </p>\r\n<p>\r\n	Regards,</p>\r\n<p>\r\n	PPB</p>\r\n', 'notification email', 'success', 'Notification and Email sent to applicant and managers after successful amendment letter approval', '2019-08-11 19:57:58', '2019-08-14 21:52:39'),
(47, 'applicant_invoice_email_subject', '', '<p>\r\n	Invoice Generation for Protocol <strong>:protocol_no</strong></p>\r\n', 'email', '', 'Sent when an invoice is generated', '2024-05-22 18:01:40', '2025-02-06 11:15:41'),
(48, 'applicant_invoice_email', '', '<p>\r\n	Dear&nbsp;<strong>:name</strong>,</p>\r\n<p>\r\n	An Invoice for your protocol titled <strong>:protocol_no</strong> has been generated, please proceed to make payment.</p>\r\n<p>\r\n	You can access the invoice here <strong>:protocol_link</strong></p>\r\n', 'email', 'success', 'sent once an invoice is generated', '2024-05-22 18:11:20', '2025-02-06 11:16:32'),
(49, 'outsource_request', 'Outsource Request for :protocol_no', '<p>\r\n	Dear :name,</p>\r\n<p>\r\n	You have a new outsource request for protocl with :protocol_no. Please review and proceed to asign the user for further processing.</p>\r\n', '', '', '', '2024-05-27 13:35:24', '2024-05-27 13:35:24'),
(50, 'outsource_email', 'Outsource request', '<p>\r\n	Dear :name</p>\r\n<p>\r\n	You have a new outsource request for protocol :protocol_no.</p>\r\n<p>\r\n	You can access the request <a href=\":protocol_link\">here</a>. Please review and submit it when you are done.</p>\r\n', 'email', '', 'Notify admins of new outsource request', '2024-05-29 08:24:03', '2024-05-29 08:36:28'),
(51, 'outsource_email_subject', 'Outsource request', '<p>\r\n	Outsource request for Protocol <strong>:protocol_no</strong></p>\r\n', '', '', '', '2024-05-29 08:25:12', '2024-05-29 08:28:58'),
(52, 'outsource_user_receive_email_subject', '', '<p>\r\n	Outsource request for Protocol <strong>:protocol_no</strong></p>\r\n', 'email', 'success', 'Alert the outsourced user of an approved outsource request', '2024-05-29 09:14:48', '2024-05-29 09:14:48'),
(53, 'outsource_user_receive_email', 'Approved Outsource request', '<p>\r\n	Dear :name</p>\r\n<p>\r\n	You have a new approved outsource request for protocol :protocol_no.</p>\r\n<p>\r\n	You can access the request <a href=\":protocol_link\">here</a>. Please review and submit it when you are done.</p>\r\n', 'email', 'warning', 'Alert the user of approved request', '2024-05-29 09:16:47', '2024-05-29 09:16:47'),
(54, 'outsource_user_receive_credentials_email', 'New Outsourced User', '<p>\r\n	Dear :name</p>\r\n<p>\r\n	You have a new approved outsource request for protocol :protocol_no. Login to your account using :username and password as :password</p>\r\n<p>\r\n	You can access the request <a href=\":protocol_link\">here</a>. Please review and submit it when you are done.</p>\r\n', 'email', 'warning', 'Alert the user of approved request', '2024-05-29 09:53:08', '2024-05-29 09:53:08'),
(55, 'safety_email_subject', 'Safety Report for :protocol_no', '<p>\r\n	<span style=\"color: rgb(102, 102, 102); font-family: Tahoma, Arial, sans-serif; font-size: 13px;\">Safety report submission for Protocol&nbsp;</span><strong style=\"color: rgb(102, 102, 102); font-family: Tahoma, Arial, sans-serif; font-size: 13px;\">:protocol_no</strong></p>\r\n', 'email', 'success', 'Sent to managers when a safety report is submitted', '2024-12-16 08:51:18', '2024-12-16 08:51:18'),
(56, 'safety_email', 'Safety Report', '<p style=\"margin: 0px 0px 10px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, sans-serif; font-size: 13px;\">\r\n	Dear :name</p>\r\n<p style=\"margin: 0px 0px 10px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, sans-serif; font-size: 13px;\">\r\n	You have a new safety report submission for protocol :protocol_no.</p>\r\n<p style=\"margin: 0px 0px 10px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, sans-serif; font-size: 13px;\">\r\n	You can access the request&nbsp;<a href=\"http://45.79.161.190:8080/manager/applications/view/:protocol_link\" style=\"color: rgb(0, 136, 204); text-decoration-line: none;\">here</a>. Please review and submit it when you are done.</p>\r\n', 'email', 'warning', 'Sent to managers', '2024-12-16 08:54:09', '2024-12-16 08:54:09'),
(57, 'annual_checklist_feedback', 'Annual Checklist Feedback', '<p>\n	Dear :name,</p>\n<p>\n	<u><strong>Annual Checklist Feedback</strong></u></p>\n<p>\n	<strong>Subject</strong>: :comment_subject</p>\n<p>\n	<strong>Content</strong></p>\n<p>\n	:comment_content</p>\n<p>\n	Link:</p>\n<p>\n	:reference_link</p>\n<p>\n	Regards,</p>\n<p>\n	PPB</p>\n', 'notification email', 'info', 'Email and notification sent during annual checklist', '2020-01-12 14:48:23', '2020-01-12 14:48:23'),
(58, 'termination_letter', 'Termination letter generated for :protocol_no', '<p>\r\n	Dear :name,</p>\r\n<p>\r\n	<u><strong>REF: :protocol_link termination</strong></u></p>\r\n<p>\r\n	New Termination letter has been generated for <strong>:protocol_link</strong>.</p>\r\n<p>\r\n	Regards,</p>\r\n<p>\r\n	PPB</p>\r\n', 'notification email', 'success', 'Notification and Email sent to applicant and managers after successfull termination of protocol', '2019-08-11 19:57:58', '2025-02-12 12:18:46'),
(59, 'admin_edit_protocol', 'Protocol Revision', '<p style=\"margin: 0px 0px 10px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, sans-serif; font-size: 13px;\">\r\n	Dear :name</p>\r\n<p style=\"margin: 0px 0px 10px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, sans-serif; font-size: 13px;\">\r\n	Your&nbsp;ECCT number has been revised to :protocol_no.</p>\r\n<p style=\"margin: 0px 0px 10px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, sans-serif; font-size: 13px;\">\r\n	You can access the report here :protocol_link </p>\r\n', 'notification', 'success', 'Sent when admins revised a protocol number', '2025-02-14 09:53:44', '2025-02-14 10:11:48'),
(60, 'unsubmitted_application_subject', 'Auto Deletion of Protocol :protocol_no', '<p>\r\n	<span style=\"color: rgb(102, 102, 102); font-family: Tahoma, Arial, sans-serif; font-size: 13px;\">Auto Deletion of Protocol created on </span><strong style=\"color: rgb(102, 102, 102); font-family: Tahoma, Arial, sans-serif; font-size: 13px;\">:protocol_no</strong></p>\r\n', 'email', 'success', 'Sent when a report stays too long without submission', '2025-02-14 12:25:13', '2025-02-14 12:33:30'),
(61, 'unsubmitted_application', '', '<p style=\"margin: 0px 0px 10px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, sans-serif; font-size: 13px;\">\r\n	Dear&nbsp;<strong>:name</strong>,</p>\r\n<p style=\"margin: 0px 0px 10px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, sans-serif; font-size: 13px;\">\r\n	Your report will soon be deleted.</p>\r\n<p style=\"margin: 0px 0px 10px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, sans-serif; font-size: 13px;\">\r\n	The unique protocol number for the application is&nbsp;<strong>:protocol_no</strong>.&nbsp;</p>\r\n', 'email', 'error', 'Sent to apllicant when a report is due for deletion', '2025-02-14 12:26:55', '2025-02-14 12:26:55'),
(62, 'initial_approval_letter', 'Initial Approval letter generated for :protocol_no', '<p>\r\n	Dear :name,</p>\r\n<p>\r\n	<u><strong>REF: :protocol_link approved for :approval_date</strong></u></p>\r\n<p>\r\n	New Initial pproval <strong>:approval_no</strong> dated <strong>:approval_date</strong> has been generated for <strong>:protocol_link</strong>. Expiry date :expiry_date</p>\r\n<p>\r\n	Regards,</p>\r\n<p>\r\n	PPB</p>\r\n', 'notification email', 'success', 'Notification and Email sent to applicant and managers after successfull initial approval', '2025-02-21 12:02:50', '2025-02-21 12:02:50'),
(63, 'reviewer_reminder_email_subject', '', '<p>\r\n	Review Reminder of Protocol <strong>:protocol_no</strong></p>\r\n', 'email', 'info', 'Email subject sent to reviewer as a reminder to submit a review', '2025-10-03 00:00:00', '2025-10-03 00:00:00'),
(64, 'reviewer_reminder_email', '', '<p>\r\n	Dear :name,</p>\r\n<p>\r\n	This is a kind reminder that a report has been assigned to you for review by the <strong>Pharmacy and Poisons Board</strong>.</p>\r\n<p>\r\n	The unique protocol number for the application is <strong>:protocol_no</strong>. Kindly <a href=\":protocol_link\" target=\"_blank\">click here to view the protocol</a> and submit your review.</p>\r\n<p>\r\n	Thank you for your attention to this matter.</p>\r\n<p>\r\n	Kind regards,<br />\r\n	<strong>Pharmacy and Poisons Board</strong></p>\r\n', 'email', 'success', 'Email sent to applicant after successfully submitting an application.', '2025-10-03 00:00:00', '2025-10-03 14:48:03');

-- --------------------------------------------------------

--
-- Table structure for table `multi_centers`
--

CREATE TABLE `multi_centers` (
  `id` int NOT NULL,
  `owner_id` int NOT NULL,
  `user_id` int NOT NULL,
  `application_id` int NOT NULL,
  `app_id` int DEFAULT NULL,
  `site_name` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `status` varchar(255) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int DEFAULT NULL,
  `type` char(70) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `foreign_key` int DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `system_message` text,
  `user_message` text,
  `deleted` tinyint(1) DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `id` int NOT NULL,
  `application_id` int DEFAULT NULL,
  `organization` varchar(255) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `telephone_number` varchar(255) DEFAULT NULL,
  `all_tasks` char(30) DEFAULT NULL,
  `monitoring` char(30) DEFAULT NULL,
  `regulatory` char(30) DEFAULT NULL,
  `investigator_recruitment` char(30) DEFAULT NULL,
  `ivrs_treatment_randomisation` char(30) DEFAULT NULL,
  `data_management` char(30) DEFAULT NULL,
  `e_data_capture` char(30) DEFAULT NULL,
  `susar_reporting` char(30) DEFAULT NULL,
  `quality_assurance_auditing` char(30) DEFAULT NULL,
  `statistical_analysis` char(30) DEFAULT NULL,
  `medical_writing` char(30) DEFAULT NULL,
  `other_duties` char(30) DEFAULT NULL,
  `other_duties_specify` text,
  `misc` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `outsources`
--

CREATE TABLE `outsources` (
  `id` int NOT NULL,
  `application_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_no` varchar(255) NOT NULL,
  `institution_physical` varchar(255) DEFAULT NULL,
  `name_of_institution` varchar(255) DEFAULT NULL,
  `institution_address` varchar(255) DEFAULT NULL,
  `institution_contact` varchar(255) DEFAULT NULL,
  `county_id` int DEFAULT NULL,
  `country_id` int NOT NULL,
  `approved` int DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `accepted` varchar(255) DEFAULT NULL,
  `model_sae` tinyint(1) DEFAULT '0',
  `model_ciom` tinyint(1) DEFAULT '0',
  `model_dev` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `outsource_requests`
--

CREATE TABLE `outsource_requests` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `application_id` int DEFAULT NULL,
  `outsource_id` int DEFAULT NULL,
  `sae` tinyint(1) DEFAULT '0',
  `ciom` tinyint(1) DEFAULT '0',
  `dev` tinyint(1) DEFAULT '0',
  `status` varchar(11) DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `participant_flows`
--

CREATE TABLE `participant_flows` (
  `id` int NOT NULL,
  `application_id` int NOT NULL,
  `year` char(50) DEFAULT NULL,
  `original_subjects` int DEFAULT NULL,
  `consented` int DEFAULT NULL,
  `screened` int DEFAULT NULL,
  `enrolled` int DEFAULT NULL,
  `lost` int DEFAULT NULL,
  `lost_reason` text,
  `withdrawn` int DEFAULT NULL,
  `withdrawal_reason` text,
  `self_withdrawal` int DEFAULT NULL,
  `self_withdrawal_reasons` text,
  `active_subjects` int DEFAULT NULL,
  `completed_number` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pharmacists`
--

CREATE TABLE `pharmacists` (
  `id` int NOT NULL,
  `application_id` int DEFAULT NULL,
  `reg_no` varchar(100) DEFAULT NULL,
  `given_name` varchar(100) DEFAULT NULL,
  `premise_name` varchar(200) DEFAULT NULL,
  `qualification` varchar(255) DEFAULT NULL,
  `professional_address` varchar(255) DEFAULT NULL,
  `valid_year` varchar(10) DEFAULT NULL,
  `mobile` varchar(100) DEFAULT NULL,
  `telephone` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `placebos`
--

CREATE TABLE `placebos` (
  `id` int NOT NULL,
  `application_id` int DEFAULT NULL,
  `placebo_present` varchar(30) DEFAULT NULL,
  `pharmaceutical_form` varchar(255) DEFAULT NULL,
  `route_of_administration` varchar(255) DEFAULT NULL,
  `composition` varchar(255) DEFAULT NULL,
  `identical_indp` varchar(30) DEFAULT NULL,
  `major_ingredients` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pockets`
--

CREATE TABLE `pockets` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL,
  `required` tinyint(1) DEFAULT '0',
  `version_required` tinyint(1) DEFAULT '0',
  `date_required` tinyint(1) DEFAULT '0',
  `item_number` int DEFAULT NULL,
  `type` char(10) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pockets`
--

INSERT INTO `pockets` (`id`, `name`, `content`, `required`, `version_required`, `date_required`, `item_number`, `type`, `deleted`, `deleted_date`, `created`, `modified`) VALUES
(1, 'financial_breakdown', '<p>\r\n	<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\">The information below is required for the study to be allowed to continue....</span></span></p>\r\n', 0, 0, 0, NULL, 'content', 0, NULL, '2019-06-29 11:57:26', '2024-01-19 08:14:19'),
(3, 'progress_report', 'Annual progress report', 1, 0, 1, 2, 'annual', 0, NULL, '2019-07-07 18:21:23', '2024-11-22 19:35:45'),
(4, 'sae_susar_log', 'SAE and SUSAR cumulative logs', 1, 1, 1, 3, 'annual', 0, NULL, '2019-07-07 18:21:57', '2024-11-22 19:35:54'),
(5, 'dsmb', 'Latest Data Safety Monitoring Board (DSMB) report', 1, 1, 1, 4, 'annual', 0, NULL, '2019-07-07 18:22:46', '2024-11-22 19:36:02'),
(6, 'protocol_violations', 'Protocol Violations and Protocol Deviations logs', 1, 0, 0, 5, 'annual', 0, NULL, '2019-07-07 18:24:18', '2024-02-21 18:36:38'),
(7, 'impd', 'Updated Investigators Brochure/Package inserts or Investigational Medicinal Product Dossier (IMPD)', 1, 1, 1, 6, 'annual', 0, NULL, '2019-07-07 18:38:06', '2024-11-22 19:36:12'),
(8, 'dsur', 'The Development Safety Update Report (DSUR)', 1, 0, 0, 12, 'annual', 0, NULL, '2019-07-08 20:00:11', '2019-07-08 20:00:11'),
(9, 'erc', 'Copy of current favourable opinion letter from the local Ethics Review Committee (ERC).', 1, 0, 0, 14, 'annual', 0, NULL, '2019-07-08 20:00:26', '2019-07-08 20:00:26'),
(10, 'annual_practice_investigator', 'Copy of the Annual Practice License of the Investigators', 1, 0, 0, 16, 'annual', 0, NULL, '2019-07-08 20:00:53', '2020-07-16 12:36:37'),
(11, 'annual_practice_pharmacist', 'Copy of the Annual Practice License for the Pharmacist', 1, 0, 0, 18, 'annual', 0, NULL, '2019-07-08 20:00:53', '2020-07-16 12:36:53'),
(12, 'indemnity_cover_investigator', 'Copy of the valid professional indemnity insurance cover for the investigators and study pharmacist', 1, 0, 0, 20, 'annual', 0, NULL, '2019-07-08 20:01:22', '2019-07-08 20:01:22'),
(14, 'insurance_cover', 'Copy of valid participantsâ€™ clinical trials insurance cover', 1, 0, 0, 24, 'annual', 0, NULL, '2019-07-08 20:01:43', '2019-07-08 20:01:43'),
(16, 'signed_checklist', 'A signed checklist confirming the submission of the document', 1, 0, 0, 28, 'annual', 0, NULL, '2019-07-08 20:02:10', '2019-08-18 12:08:06'),
(17, 'initial_approval_letter', '<h3 style=\"text-align: center;\">\r\n	<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\"><img height=\"86\" src=\"https://lh7-us.googleusercontent.com/eOWVvo3AHW74GguHveDaqdgy3YwNZqOqOO0QtscRoV4hJfcvt8Q6v4oLN3beVNvClvoD1ncu1RhB5D4iuAY-5R9h1aD2lEGqUorBVcQ0azfxsdvIz-WhbF3rA9-VQomtusnP2bNZTuYLFTC6vQ46nQ\" style=\"background-color: transparent; color: rgb(0, 0, 0); font-family: &quot;Bookman Old Style&quot;, serif; font-size: 11pt; white-space-collapse: preserve; margin-left: 0px; margin-top: 0px;\" width=\"116\" /></span></span></h3>\r\n<p style=\"text-align: center;\">\r\n	<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\"><strong>MINISTRY</strong> <strong>OF</strong> <strong>HEALTH</strong></span></span></p>\r\n<p style=\"text-align: center;\">\r\n	<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\"><strong>PHARMACY</strong> <strong>AND</strong> <strong>POISONS</strong> <strong>BOARD</strong></span></span></p>\r\n<table style=\"width: 100%; border:0px\">\r\n	<tbody>\r\n		<tr>\r\n			<td style=\"width: 33.33%; border:0px\">\r\n				<div id=\"left-editor\">\r\n					<p>\r\n						<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\">Telegram: &quot;MINHEALTH&quot;, Nairobi</span></span></p>\r\n					<p>\r\n						<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\">Cell phone: 0709 770 100</span></span></p>\r\n					<p>\r\n						<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\">Email: info@pharmacyboardkenya.org</span></span></p>\r\n					<p>\r\n						<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\">Website: web.pharmacyboardkenya.org</span></span></p>\r\n				</div>\r\n			</td>\r\n			<td style=\"width: 33.33%;border:0px\">\r\n				&nbsp;</td>\r\n			<td style=\"width: 33.33%;border:0px\">\r\n				<div id=\"right-editor\">\r\n					<p>\r\n						<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\">Pharmacy and Poisons Board House</span></span></p>\r\n					<p>\r\n						<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\">Along Lenana Road </span></span></p>\r\n					<p>\r\n						<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\">P.O. Box 27663-00506 </span></span></p>\r\n					<p>\r\n						<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\">NAIROBI </span></span></p>\r\n				</div>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n<address>\r\n	<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\">&nbsp;</span></span></address>\r\n<address>\r\n	<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\">When replying please quote</span></span></address>\r\n<p>\r\n	<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\"><strong>Ref.</strong> <strong>No.</strong> <strong>:approval_no&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :letter_date</strong></span></span></p>\r\n<p>\r\n	<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\"><strong>:names,</strong></span></span></p>\r\n<p>\r\n	<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\"><strong>Principal</strong> <strong>Investigator</strong> <strong>:protocol_no,</strong></span></span></p>\r\n<p>\r\n	<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\"><strong>:professional_address,</strong></span></span></p>\r\n<p>\r\n	<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\">Dear Sir/Madam,</span></span></p>\r\n<p>\r\n	<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\"><strong><u>Re: :protocol_no : Initial Approval; :study_title&nbsp; :short_title</u></strong></span><strong style=\"font-family: &quot;bookman old style&quot;, serif;\"><u>.</u></strong></span></p>\r\n<p>\r\n	<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\">Reference is made to the above study.</span></span></p>\r\n<p>\r\n	<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\">We acknowledge receipt of the following documents;</span></span></p>\r\n<div style=\"margin-left: 15px;\">\r\n	<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\">:checklist</span></span></div>\r\n<p>\r\n	<span style=\"font-size:12px;\">After review of the documents, the Pharmacy and Poisons Board Expert Committee on Clinical Trials notes the following:</span></p>\r\n<p>\r\n	<span style=\"font-size:12px;\"><strong>&nbsp; &nbsp; &nbsp; &nbsp; :reviewer_summary_comments</strong></span></p>\r\n<p>\r\n	<span style=\"font-size:12px;\">The Pharmacy and Poisons Board Expert Committee on Clinical Trials grants approval to the study: :study_title :protocol_no<strong>.</strong></span></p>\r\n<p>\r\n	<span style=\"font-size:12px;\">This approval is&nbsp;<strong>valid</strong>&nbsp;<strong>for</strong>&nbsp;<strong>one</strong>&nbsp;<strong>year</strong>&nbsp;and in case the study extends beyond one year from the date of this letter, you are required to seek approval before proceeding with the study. The expiry date is <b>:expiry_date</b></span></p>\r\n<ol>\r\n	<li>\r\n		<span style=\"font-size:12px;\">You should ensure compliance with Kenya Legal, regulatory and GCP requirements</span></li>\r\n	<li>\r\n		<span style=\"font-size:12px;\">All safety reports should be submitted to ECCT as per the current PPB clinical trials guidelines</span></li>\r\n	<li>\r\n		<span style=\"font-size:12px;\">It is your responsibility to inform the PPB of any changes to the protocol, research design and procedures that could introduce new or more than minimum risk to human subjects</span></li>\r\n	<li>\r\n		<span style=\"font-size:12px;\">You are also reminded that upon conclusion of the study, you shall be required to submit the executive summary report of the study within 30 days while a copy of the clinical study report in ICH E3 format should be submitted to us within 180 days of the study closure.</span></li>\r\n</ol>\r\n<div>\r\n	<p>\r\n		<span style=\"font-size:12px;\">Yours Sincerely,</span></p>\r\n</div>\r\n<p>\r\n	<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\">:signature</span></span></p>\r\n<p>\r\n	<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\">:approver</span></span></p>\r\n<p>\r\n	<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\"><strong><u>For Chief Executive Officer</u></strong></span></span></p>\r\n<p>\r\n	&nbsp;</p>\r\n', 0, 0, 0, NULL, 'content', 0, NULL, '2019-08-10 12:52:12', '2025-02-17 15:25:46'),
(18, 'annual_approval_letter', '<h3 style=\"text-align: center;\">\r\n	<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\"><img height=\"86\" src=\"https://lh7-us.googleusercontent.com/eOWVvo3AHW74GguHveDaqdgy3YwNZqOqOO0QtscRoV4hJfcvt8Q6v4oLN3beVNvClvoD1ncu1RhB5D4iuAY-5R9h1aD2lEGqUorBVcQ0azfxsdvIz-WhbF3rA9-VQomtusnP2bNZTuYLFTC6vQ46nQ\" style=\"background-color: transparent; color: rgb(0, 0, 0); font-family: &quot;Bookman Old Style&quot;, serif; font-size: 11pt; white-space-collapse: preserve; margin-left: 0px; margin-top: 0px;\" width=\"116\" /></span></span></h3>\r\n<p style=\"text-align: center;\">\r\n	<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\"><strong>MINISTRY</strong> <strong>OF</strong> <strong>HEALTH</strong></span></span></p>\r\n<p style=\"text-align: center;\">\r\n	<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\"><strong>PHARMACY</strong> <strong>AND</strong> <strong>POISONS</strong> <strong>BOARD</strong></span></span></p>\r\n<table style=\"width: 100%; border:0px\">\r\n	<tbody>\r\n		<tr>\r\n			<td style=\"width: 33.33%; border:0px\">\r\n				<div id=\"left-editor\">\r\n					<p>\r\n						<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\">Telegram: &quot;MINHEALTH&quot;, Nairobi</span></span></p>\r\n					<p>\r\n						<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\">Cell phone: 0709 770 100</span></span></p>\r\n					<p>\r\n						<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\">Email: info@pharmacyboardkenya.org</span></span></p>\r\n					<p>\r\n						<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\">Website: web.pharmacyboardkenya.org</span></span></p>\r\n				</div>\r\n			</td>\r\n			<td style=\"width: 33.33%;border:0px\">\r\n				&nbsp;</td>\r\n			<td style=\"width: 33.33%;border:0px\">\r\n				<div id=\"right-editor\">\r\n					<p>\r\n						<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\">Pharmacy and Poisons Board House</span></span></p>\r\n					<p>\r\n						<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\">Along Lenana Road </span></span></p>\r\n					<p>\r\n						<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\">P.O. Box 27663-00506 </span></span></p>\r\n					<p>\r\n						<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\">NAIROBI </span></span></p>\r\n				</div>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n<address>\r\n	<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\">&nbsp;</span></span></address>\r\n<address>\r\n	<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\">When replying please quote</span></span></address>\r\n<p>\r\n	<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\"><strong>Ref.</strong> <strong>No.</strong> <strong>:approval_no&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :letter_date</strong></span></span></p>\r\n<p>\r\n	<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\"><strong>:names,</strong></span></span></p>\r\n<p>\r\n	<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\"><strong>Principal</strong> <strong>Investigator</strong> <strong>:protocol_no,</strong></span></span></p>\r\n<p>\r\n	<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\"><strong>:professional_address,</strong></span></span></p>\r\n<p>\r\n	<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\">Dear Sir/Madam,</span></span></p>\r\n<p>\r\n	<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\"><strong><u>Re: :protocol_no : Annual Approval; :study_title&nbsp; :short_title</u></strong></span><strong style=\"font-family: &quot;bookman old style&quot;, serif;\"><u>.</u></strong></span></p>\r\n<p>\r\n	<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\">Reference is made to the above study.</span></span></p>\r\n<p>\r\n	<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\">We acknowledge receipt of the following documents;</span></span></p>\r\n<div style=\"margin-left: 15px;\">\r\n	<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\">:checklist</span></span></div>\r\n<p>\r\n	<span style=\"font-size:12px;\">After review of the documents, the Pharmacy and Poisons Board Expert Committee on Clinical Trials notes the following:</span></p>\r\n<p>\r\n	<span style=\"font-size:12px;\">&nbsp; &nbsp; &nbsp; &nbsp;:reviewer_summary_comments</span></p>\r\n<p>\r\n	<span style=\"font-size:12px;\">The Pharmacy and Poisons Board Expert Committee on Clinical Trials grants approval to the study: :study_title :protocol_no<strong>.</strong></span></p>\r\n<p>\r\n	<span style=\"font-size:12px;\">This approval is&nbsp;<strong>valid</strong>&nbsp;<strong>for</strong>&nbsp;<strong>one</strong>&nbsp;<strong>year</strong>&nbsp;and in case the study extends beyond one year from the date of this letter, you are required to seek approval before proceeding with the study. The expiry date is <b>:expiry_date</b></span></p>\r\n<ol>\r\n	<li>\r\n		<span style=\"font-size:12px;\">You should ensure compliance with Kenya Legal, regulatory and GCP requirements</span></li>\r\n	<li>\r\n		<span style=\"font-size:12px;\">All safety reports should be submitted to ECCT as per the current PPB clinical trials guidelines</span></li>\r\n	<li>\r\n		<span style=\"font-size:12px;\">It is your responsibility to inform the PPB of any changes to the protocol, research design and procedures that could introduce new or more than minimum risk to human subjects</span></li>\r\n	<li>\r\n		<span style=\"font-size:12px;\">You are also reminded that upon conclusion of the study, you shall be required to submit the executive summary report of the study within 30 days while a copy of the clinical study report in ICH E3 format should be submitted to us within 180 days of the study closure</span></li>\r\n</ol>\r\n<div>\r\n	<p>\r\n		<span style=\"font-size:12px;\">Yours Sincerely,</span></p>\r\n</div>\r\n<p>\r\n	<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\">:signature</span></span></p>\r\n<p>\r\n	<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\">:approver</span></span></p>\r\n<p>\r\n	<span style=\"font-size:12px;\"><span style=\"font-family:bookman old style,serif;\"><strong><u>For Chief Executive Officer</u></strong></span></span></p>\r\n<p>\r\n	&nbsp;</p>\r\n', 0, 0, 0, NULL, 'content', 0, NULL, '2019-08-13 18:41:05', '2024-12-05 16:19:33'),
(19, 'applicant_covering_letter', 'Cover Letter (Should list all the submitted documents, their version numbers and dates)', 1, 0, 0, 1, 'protocol', 0, NULL, '2019-08-13 21:05:34', '2025-02-28 14:49:43'),
(20, 'applicant_complete_form', 'Completed application form', 1, 0, 0, 2, 'protocol', NULL, '2019-11-25 10:00:00', '2019-08-13 21:08:13', '2019-08-13 21:08:13'),
(21, 'applicant_protocol', 'The Study Protocol', 1, 0, 0, 2, 'protocol', 0, NULL, '2019-08-13 21:08:33', '2020-07-16 12:11:49'),
(22, 'applicant_pan_african', 'Registration of the study at Pan African Clinical Trials Registry https://pactr.samrc.ac.za', 1, 0, 1, 3, 'protocol', 0, NULL, '2019-08-13 21:09:01', '2025-01-06 14:04:03'),
(23, 'applicant_patient_information', 'Patient Information leaflet and Informed consent form', 1, 0, 0, 4, 'protocol', 0, NULL, '2019-08-13 21:09:47', '2020-07-16 12:12:00'),
(24, 'applicant_investigators_brochure', 'Investigators Brochure/Package inserts', 1, 0, 0, 5, 'protocol', 0, NULL, '2019-08-13 21:10:06', '2020-07-16 12:12:07'),
(25, 'applicant_impd', 'Investigational Medicinal Product Dossier (IMPD)', 1, 0, 0, 6, 'protocol', 0, NULL, '2019-08-13 21:10:25', '2020-07-16 12:12:54'),
(26, 'applicant_prev_data', 'Adequate data and information from previous studies and Phases to support carrying out of the current study', 1, 0, 0, 7, 'protocol', 0, NULL, '2019-08-13 21:10:52', '2020-07-16 12:13:09'),
(27, 'applicant_stability_data', 'Stability data of the investigational product', 1, 0, 0, 8, 'protocol', 0, NULL, '2019-08-13 21:11:16', '2020-07-16 12:16:11'),
(28, 'applicant_gmp_certificate', 'GMP certificate of the investigational product from the site of manufacture', 1, 0, 1, 9, 'protocol', 0, NULL, '2019-08-13 21:11:44', '2025-01-06 14:04:51'),
(29, 'applicant_analysis_cert', 'Certificate of Analysis of the investigational product', 1, 0, 1, 10, 'protocol', 0, NULL, '2019-08-13 21:12:00', '2025-01-06 14:05:23'),
(30, 'applicant_pictorial_sample', 'Pictorial Sample of the investigational products. This sample should include the text of the labeling to be used', 1, 0, 0, 11, 'protocol', 0, NULL, '2019-08-13 21:12:19', '2020-07-16 12:16:44'),
(31, 'applicant_investigators_cv', 'Signed investigator(s) CV(s) including that of study Pharmacist (NB: The CV should include the current workload of the Principal Investigator )', 1, 0, 1, 12, 'protocol', 0, NULL, '2019-08-13 21:13:10', '2025-01-06 14:05:52'),
(32, 'applicant_gcp_training', 'Evidence of recent GCP training of the core study staff', 1, 0, 1, 14, 'protocol', 0, NULL, '2019-08-13 21:13:44', '2025-01-06 14:06:34'),
(33, 'applicant_dsmb_charter', 'DSMB Charter including the composition and meeting schedule', 1, 0, 0, 15, 'protocol', 0, NULL, '2019-08-13 21:14:06', '2019-08-13 21:14:06'),
(34, 'applicant_detailed_budget', 'Detailed budget of the study', 1, 0, 0, 16, 'protocol', 0, NULL, '2019-08-13 21:14:23', '2019-08-13 21:14:23'),
(35, 'applicant_financial_declaration', 'Financial declaration by Sponsor and/or PI <a href=\"http://45.79.161.190:8080/attachments/export/1736\">click here</a>', 1, 0, 1, 17, 'protocol', 0, NULL, '2019-08-13 21:14:41', '2025-04-11 11:42:08'),
(36, 'applicant_signed_declaration', 'Signed Declaration by Sponsor or Principal investigator that the study will be carried out according to the protocol and applicable laws, regulations and GCP requirements. <a href=\"http://45.79.161.190:8080/attachments/export/1732\"> click here </a>', 1, 0, 1, 18, 'protocol', 0, NULL, '2019-08-13 21:15:06', '2025-04-11 11:28:12'),
(37, 'applicant_indemnity_pi', 'Indemnity cover for PI,  investigators and study Pharmacist. ', 1, 0, 1, 19, 'protocol', 0, NULL, '2019-08-13 21:15:25', '2025-01-06 14:09:22'),
(38, 'applicant_indemnity_cover', 'Clinical Trials Insurance cover for study participants', 1, 0, 1, 20, 'protocol', 0, NULL, '2019-08-13 21:15:46', '2025-01-06 14:09:48'),
(39, 'applicant_opinion_letter', 'Copy of favorable opinion letter from the local Ethics Review Committee (ERC).', 1, 0, 1, 21, 'protocol', 0, NULL, '2019-08-13 21:16:07', '2025-01-06 14:10:39'),
(40, 'applicant_practice_license', 'Copy of current Practice Licenses for the Investigators and study Pharmacist', 1, 0, 1, 22, 'protocol', 0, NULL, '2019-08-13 21:16:26', '2025-01-06 14:11:43'),
(41, 'applicant_approval_letter', 'Copy of approval letter(s) from collaborating institutions or other regulatory authorities, if applicable', 1, 0, 1, 23, 'protocol', 0, NULL, '2019-08-13 21:16:49', '2025-01-06 14:12:53'),
(42, 'applicant_participating_countries', 'Where the trial is part of an international study, sufficient information regarding the other participating countries and the scope of the study in these countries.', 0, 0, 0, 24, 'protocol', NULL, '2020-01-02 00:00:00', '2019-08-13 21:17:18', '2019-08-13 21:17:18'),
(43, 'applicant_addendum', 'For multicentre/multi-site studies, a site specific addendum for each of the proposed sites including among other things the sitesâ€™ capacity to carry out the study i.e personnel, equipment, laboratory etc', 0, 0, 0, 24, 'protocol', 0, NULL, '2019-08-13 21:17:43', '2020-07-16 10:52:29'),
(44, 'applicant_statement', 'A signed statement by the applicant indicating that all information contained in, or referenced by, the application is complete and accurate and is not false or misleading.', 1, 0, 1, 25, 'protocol', 0, NULL, '2019-08-13 21:18:05', '2025-01-06 14:13:35'),
(45, 'applicant_fees', 'Payment of fees', 1, 0, 1, 26, 'protocol', 0, NULL, '2019-08-13 21:18:24', '2025-01-06 14:14:13'),
(46, 'applicant_signed_checklist', 'Signed Checklist', 1, 0, 1, 29, 'protocol', 0, NULL, '2019-08-13 21:18:43', '2025-01-06 14:14:43'),
(47, 'cover_letter', 'Cover Letter (Should list all the submitted documents, their version numbers and dates)', 1, 0, 1, 1, 'annual', 0, NULL, '2020-03-15 22:16:09', '2024-11-22 19:35:37'),
(48, 'statistical_analysis_plan', 'Statistical Analysis Plan', 1, 0, 0, 28, 'protocol', 0, NULL, '2020-07-16 10:55:22', '2020-07-16 10:55:22'),
(49, 'applicant_contractual_agreement', 'Evidence of contractual agreement between sponsor and Principal Investigator.', 1, 0, 0, 13, 'protocol', 0, NULL, '2020-07-16 11:12:30', '2020-07-16 12:19:41'),
(50, 'screening_comment', '<h3 style=\"text-align: center;\">\n	<img height=\"86\" src=\"https://lh7-us.googleusercontent.com/eOWVvo3AHW74GguHveDaqdgy3YwNZqOqOO0QtscRoV4hJfcvt8Q6v4oLN3beVNvClvoD1ncu1RhB5D4iuAY-5R9h1aD2lEGqUorBVcQ0azfxsdvIz-WhbF3rA9-VQomtusnP2bNZTuYLFTC6vQ46nQ\" style=\"background-color: transparent; color: rgb(0, 0, 0); font-family: &quot;Bookman Old Style&quot;, serif; font-size: 11pt; white-space-collapse: preserve; margin-left: 0px; margin-top: 0px;\" width=\"116\" /></h3>\n<p style=\"text-align: center;\">\n	<span style=\"font-family:bookman old style,serif;\"><strong>MINISTRY</strong> <strong>OF</strong> <strong>HEALTH</strong></span></p>\n<p style=\"text-align: center;\">\n	<span style=\"font-family:bookman old style,serif;\"><strong>PHARMACY</strong> <strong>AND</strong> <strong>POISONS</strong> <strong>BOARD</strong></span></p>\n<table style=\"width: 100%; border:0px\">\n	<tbody>\n		<tr>\n			<td style=\"width: 33.33%; border:0px\">\n				<div id=\"left-editor\">\n					<p>\n						<span style=\"font-family:bookman old style,serif;\"><span style=\"font-size:12px;\">Telegram: &quot;MINHEALTH&quot;, Nairobi</span></span></p>\n					<p>\n						<span style=\"font-family:bookman old style,serif;\"><span style=\"font-size:12px;\">Cell phone: 0709 770 100</span></span></p>\n					<p>\n						<span style=\"font-family:bookman old style,serif;\"><span style=\"font-size:12px;\">Email: info@pharmacyboardkenya.org</span></span></p>\n					<p>\n						<span style=\"font-family:bookman old style,serif;\"><span style=\"font-size:12px;\">Website: web.pharmacyboardkenya.org</span></span></p>\n				</div>\n			</td>\n			<td style=\"width: 33.33%;border:0px\">\n				&nbsp;</td>\n			<td style=\"width: 33.33%;border:0px\">\n				<div id=\"right-editor\">\n					<p>\n						<span style=\"font-family:bookman old style,serif;\">Pharmacy and Poisons Board House</span></p>\n					<p>\n						<span style=\"font-family:bookman old style,serif;\">Along Lenana Road </span></p>\n					<p>\n						<span style=\"font-family:bookman old style,serif;\">P.O. Box 27663-00506 </span></p>\n					<p>\n						<span style=\"font-family:bookman old style,serif;\">NAIROBI </span></p>\n				</div>\n			</td>\n		</tr>\n	</tbody>\n</table>\n', 0, 0, 0, NULL, NULL, 0, NULL, '2024-01-16 00:00:00', '2024-02-14 06:12:55'),
(51, 'review_comment', '<h3 style=\"text-align: center;\">\n	<img height=\"86\" src=\"https://lh7-us.googleusercontent.com/eOWVvo3AHW74GguHveDaqdgy3YwNZqOqOO0QtscRoV4hJfcvt8Q6v4oLN3beVNvClvoD1ncu1RhB5D4iuAY-5R9h1aD2lEGqUorBVcQ0azfxsdvIz-WhbF3rA9-VQomtusnP2bNZTuYLFTC6vQ46nQ\" style=\"background-color: transparent; color: rgb(0, 0, 0); font-family: &quot;Bookman Old Style&quot;, serif; font-size: 11pt; white-space-collapse: preserve; margin-left: 0px; margin-top: 0px;\" width=\"116\" /></h3>\n<p style=\"text-align: center;\">\n	<span style=\"font-family:bookman old style,serif;\"><strong>MINISTRY</strong> <strong>OF</strong> <strong>HEALTH</strong></span></p>\n<p style=\"text-align: center;\">\n	<span style=\"font-family:bookman old style,serif;\"><strong>PHARMACY</strong> <strong>AND</strong> <strong>POISONS</strong> <strong>BOARD</strong></span></p>\n<table style=\"width: 100%; border:0px\">\n	<tbody>\n		<tr>\n			<td style=\"width: 33.33%; border:0px\">\n				<div id=\"left-editor\">\n					<p>\n						<span style=\"font-family:bookman old style,serif;\"><span style=\"font-size:12px;\">Telegram: &quot;MINHEALTH&quot;, Nairobi</span></span></p>\n					<p>\n						<span style=\"font-family:bookman old style,serif;\"><span style=\"font-size:12px;\">Cell phone: 0709 770 100</span></span></p>\n					<p>\n						<span style=\"font-family:bookman old style,serif;\"><span style=\"font-size:12px;\">Email: info@pharmacyboardkenya.org</span></span></p>\n					<p>\n						<span style=\"font-family:bookman old style,serif;\"><span style=\"font-size:12px;\">Website: web.pharmacyboardkenya.org</span></span></p>\n				</div>\n			</td>\n			<td style=\"width: 33.33%;border:0px\">\n				&nbsp;</td>\n			<td style=\"width: 33.33%;border:0px\">\n				<div id=\"right-editor\">\n					<p>\n						<span style=\"font-family:bookman old style,serif;\">Pharmacy and Poisons Board House</span></p>\n					<p>\n						<span style=\"font-family:bookman old style,serif;\">Along Lenana Road </span></p>\n					<p>\n						<span style=\"font-family:bookman old style,serif;\">P.O. Box 27663-00506 </span></p>\n					<p>\n						<span style=\"font-family:bookman old style,serif;\">NAIROBI </span></p>\n				</div>\n			</td>\n		</tr>\n	</tbody>\n</table>\n', 0, 0, 0, NULL, NULL, 0, NULL, '2024-01-16 00:00:00', '2024-02-14 06:13:20'),
(52, 'amendment_letter', '<h3 style=\"text-align: center;\">\r\n	<span style=\"font-family:bookman old style,serif;\"><img height=\"86\" src=\"https://lh7-us.googleusercontent.com/eOWVvo3AHW74GguHveDaqdgy3YwNZqOqOO0QtscRoV4hJfcvt8Q6v4oLN3beVNvClvoD1ncu1RhB5D4iuAY-5R9h1aD2lEGqUorBVcQ0azfxsdvIz-WhbF3rA9-VQomtusnP2bNZTuYLFTC6vQ46nQ\" style=\"background-color: transparent; color: rgb(0, 0, 0); font-family: &quot;Bookman Old Style&quot;, serif; font-size: 11pt; white-space-collapse: preserve; margin-left: 0px; margin-top: 0px;\" width=\"116\" /></span></h3>\r\n<p style=\"text-align: center;\">\r\n	<span style=\"font-family:bookman old style,serif;\"><strong>MINISTRY</strong> <strong>OF</strong> <strong>HEALTH</strong></span></p>\r\n<p style=\"text-align: center;\">\r\n	<span style=\"font-family:bookman old style,serif;\"><strong>PHARMACY</strong> <strong>AND</strong> <strong>POISONS</strong> <strong>BOARD</strong></span></p>\r\n<table style=\"width: 100%; border:0px\">\r\n	<tbody>\r\n		<tr>\r\n			<td style=\"width: 33.33%; border:0px\">\r\n				<div id=\"left-editor\">\r\n					<p>\r\n						<span style=\"font-family:bookman old style,serif;\"><span style=\"font-size:12px;\">Telegram: &quot;MINHEALTH&quot;, Nairobi</span></span></p>\r\n					<p>\r\n						<span style=\"font-family:bookman old style,serif;\"><span style=\"font-size:12px;\">Cell phone: 0709 770 100</span></span></p>\r\n					<p>\r\n						<span style=\"font-family:bookman old style,serif;\"><span style=\"font-size:12px;\">Email: info@pharmacyboardkenya.org</span></span></p>\r\n					<p>\r\n						<span style=\"font-family:bookman old style,serif;\"><span style=\"font-size:12px;\">Website: web.pharmacyboardkenya.org</span></span></p>\r\n				</div>\r\n			</td>\r\n			<td style=\"width: 33.33%;border:0px\">\r\n				&nbsp;</td>\r\n			<td style=\"width: 33.33%;border:0px\">\r\n				<div id=\"right-editor\">\r\n					<p>\r\n						<span style=\"font-family:bookman old style,serif;\">Pharmacy and Poisons Board House</span></p>\r\n					<p>\r\n						<span style=\"font-family:bookman old style,serif;\">Along Lenana Road </span></p>\r\n					<p>\r\n						<span style=\"font-family:bookman old style,serif;\">P.O. Box 27663-00506 </span></p>\r\n					<p>\r\n						<span style=\"font-family:bookman old style,serif;\">NAIROBI </span></p>\r\n					<p>\r\n						<span style=\"font-family:bookman old style,serif;\"><strong>:letter_date </strong></span></p>\r\n				</div>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n<p>\r\n	<span style=\"font-family:bookman old style,serif;\">Dear Sir/Madam,</span></p>\r\n<p>\r\n	<span style=\"font-family:bookman old style,serif;\"><strong><u>Re: :protocol_no : Amendment Approval; :study_title.</u></strong></span></p>\r\n<p>\r\n	<span style=\"font-family:bookman old style,serif;\">Reference is made to the above study.</span></p>\r\n<p>\r\n	<span style=\"font-family:bookman old style,serif;\">We acknowledge receipt of the following documents;</span></p>\r\n<div style=\"margin-left: 15px;\">\r\n	<span style=\"font-family:bookman old style,serif;\">:checklist</span></div>\r\n<p>\r\n	<span style=\"font-family:bookman old style,serif;\">After review of the documents, the Pharmacy and Poisons Board Expert Committee on Clinical Trials grants amendment approval to the study <strong>:study_title</strong><strong>.</strong> <strong>(:protocol_no).</strong></span></p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	<span style=\"font-family:bookman old style,serif;\">Yours Sincerely,</span></p>\r\n<p>\r\n	<span style=\"font-family:bookman old style,serif;\">:signature</span></p>\r\n<p>\r\n	<span style=\"font-family:bookman old style,serif;\">:approver</span></p>\r\n<p>\r\n	<span style=\"font-family:bookman old style,serif;\"><strong><u>For Chief Executive Officer</u></strong></span></p>\r\n<p>\r\n	&nbsp;</p>\r\n', 1, 0, 0, 2, 'content', 0, NULL, '2024-02-11 16:27:13', '2024-07-09 08:59:23'),
(53, 'invoice', '<div class=\"container\">\r\n	<div class=\"invoice-header\">\r\n		<h3 style=\"text-align: center;\">\r\n			<span style=\"font-family:bookman old style,serif;\"><img height=\"86\" src=\"https://lh7-us.googleusercontent.com/eOWVvo3AHW74GguHveDaqdgy3YwNZqOqOO0QtscRoV4hJfcvt8Q6v4oLN3beVNvClvoD1ncu1RhB5D4iuAY-5R9h1aD2lEGqUorBVcQ0azfxsdvIz-WhbF3rA9-VQomtusnP2bNZTuYLFTC6vQ46nQ\" style=\"background-color: transparent; color: rgb(0, 0, 0); font-family: &quot;Bookman Old Style&quot;, serif; font-size: 11pt; white-space-collapse: preserve; margin-left: 0px; margin-top: 0px;\" width=\"116\" /></span></h3>\r\n		<p style=\"text-align: center;\">\r\n			<span style=\"font-family:bookman old style,serif;\"><strong>PHARMACY</strong> <strong>AND</strong> <strong>POISONS</strong> <strong>BOARD</strong></span></p>\r\n		<p>\r\n			<span style=\"font-family:bookman old style,serif;\">0709 770 100 | info@pharmacyboardkenya.org | web.pharmacyboardkenya.org</span></p>\r\n	</div>\r\n	<div class=\"invoice-details\">\r\n		<p>\r\n			<span style=\"font-family:bookman old style,serif;\">Invoice #: [Invoice Number]</span></p>\r\n		<p>\r\n			<span style=\"font-family:bookman old style,serif;\">Date: [Date]</span></p>\r\n		<p>\r\n			<span style=\"font-family:bookman old style,serif;\">Due Date: [Due Date]</span></p>\r\n	</div>\r\n	<table style=\"width: 100%; border:1px\">\r\n		<thead>\r\n			<tr>\r\n				<th>\r\n					<span style=\"font-family:bookman old style,serif;\">Item</span></th>\r\n				<th>\r\n					<span style=\"font-family:bookman old style,serif;\">Description</span></th>\r\n				<th>\r\n					<span style=\"font-family:bookman old style,serif;\">Quantity</span></th>\r\n				<th>\r\n					<span style=\"font-family:bookman old style,serif;\">Price</span></th>\r\n				<th>\r\n					<span style=\"font-family:bookman old style,serif;\">Total</span></th>\r\n			</tr>\r\n		</thead>\r\n		<tbody>\r\n			<tr>\r\n				<td>\r\n					<span style=\"font-family:bookman old style,serif;\">1.</span></td>\r\n				<td>\r\n					<span style=\"font-family:bookman old style,serif;\">[Item Description]</span></td>\r\n				<td>\r\n					<span style=\"font-family:bookman old style,serif;\">[Quantity]</span></td>\r\n				<td>\r\n					<span style=\"font-family:bookman old style,serif;\">[Price]</span></td>\r\n				<td>\r\n					<span style=\"font-family:bookman old style,serif;\">[Total]</span></td>\r\n			</tr>\r\n			<!-- Additional rows for more items -->\r\n		</tbody>\r\n	</table>\r\n	<div class=\"total\">\r\n		<p>\r\n			<span style=\"font-family:bookman old style,serif;\">Subtotal: [Subtotal Amount]</span></p>\r\n		<p>\r\n			<span style=\"font-family:bookman old style,serif;\">Tax: [Tax Amount]</span></p>\r\n		<p>\r\n			<span style=\"font-family:bookman old style,serif;\">Total: [Total Amount]</span></p>\r\n	</div>\r\n</div>\r\n<style type=\"text/css\">\r\nbody {\r\n      font-family: Arial, sans-serif;\r\n      margin: 0;\r\n      padding: 20px;\r\n    }\r\n    .container {\r\n      max-width: 600px;\r\n      margin: 0 auto;\r\n    }\r\n    .invoice-header {\r\n      text-align: center;\r\n      margin-bottom: 20px;\r\n    }\r\n    .invoice-details {\r\n      margin-bottom: 20px;\r\n    }\r\n    table {\r\n      width: 100%;\r\n      border-collapse: collapse;\r\n    }\r\n    th, td {\r\n      border: 1px solid #ddd;\r\n      padding: 8px;\r\n      text-align: left;\r\n    }\r\n    th {\r\n      background-color: #f2f2f2;\r\n    }\r\n    .total {\r\n      text-align: right;\r\n    }</style>\r\n', 0, 0, 0, NULL, NULL, 0, '2024-03-04 00:00:00', '2024-03-04 00:00:00', '2024-03-12 04:05:11'),
(65, 'amendment_cover_letter', 'Cover letter ', 1, 0, 0, 1, 'amendment', 0, NULL, '2024-07-30 14:55:50', '2025-01-15 09:35:16'),
(68, 'letter_comment', '<h3 style=\"text-align: center;\">\r\n	<img height=\"86\" src=\"https://lh7-us.googleusercontent.com/eOWVvo3AHW74GguHveDaqdgy3YwNZqOqOO0QtscRoV4hJfcvt8Q6v4oLN3beVNvClvoD1ncu1RhB5D4iuAY-5R9h1aD2lEGqUorBVcQ0azfxsdvIz-WhbF3rA9-VQomtusnP2bNZTuYLFTC6vQ46nQ\" style=\"background-color: transparent; color: rgb(0, 0, 0); font-family: &quot;Bookman Old Style&quot;, serif; font-size: 11pt; white-space-collapse: preserve; margin-left: 0px; margin-top: 0px;\" width=\"116\" /></h3>\r\n<p style=\"text-align: center;\">\r\n	<span style=\"font-family:bookman old style,serif;\"><strong>MINISTRY</strong> <strong>OF</strong> <strong>HEALTH</strong></span></p>\r\n<p style=\"text-align: center;\">\r\n	<span style=\"font-family:bookman old style,serif;\"><strong>PHARMACY</strong> <strong>AND</strong> <strong>POISONS</strong> <strong>BOARD</strong></span></p>\r\n<table style=\"width: 100%; border:0px\">\r\n	<tbody>\r\n		<tr>\r\n			<td style=\"width: 33.33%; border:0px\">\r\n				<div id=\"left-editor\">\r\n					<p>\r\n						<span style=\"font-family:bookman old style,serif;\"><span style=\"font-size:12px;\">Telegram: &quot;MINHEALTH&quot;, Nairobi</span></span></p>\r\n					<p>\r\n						<span style=\"font-family:bookman old style,serif;\"><span style=\"font-size:12px;\">Cell phone: 0709 770 100</span></span></p>\r\n					<p>\r\n						<span style=\"font-family:bookman old style,serif;\"><span style=\"font-size:12px;\">Email: info@pharmacyboardkenya.org</span></span></p>\r\n					<p>\r\n						<span style=\"font-family:bookman old style,serif;\"><span style=\"font-size:12px;\">Website: web.pharmacyboardkenya.org</span></span></p>\r\n				</div>\r\n			</td>\r\n			<td style=\"width: 33.33%;border:0px\">\r\n				&nbsp;</td>\r\n			<td style=\"width: 33.33%;border:0px\">\r\n				<div id=\"right-editor\">\r\n					<p>\r\n						<span style=\"font-family:bookman old style,serif;\">Pharmacy and Poisons Board House</span></p>\r\n					<p>\r\n						<span style=\"font-family:bookman old style,serif;\">Along Lenana Road </span></p>\r\n					<p>\r\n						<span style=\"font-family:bookman old style,serif;\">P.O. Box 27663-00506 </span></p>\r\n					<p>\r\n						<span style=\"font-family:bookman old style,serif;\">NAIROBI </span></p>\r\n				</div>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n', 0, 0, 0, NULL, NULL, 0, NULL, '2024-11-20 00:00:00', '2024-11-20 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `previous_dates`
--

CREATE TABLE `previous_dates` (
  `id` int NOT NULL,
  `application_id` int DEFAULT NULL,
  `date_of_previous_protocol` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `principal_investigators`
--

CREATE TABLE `principal_investigators` (
  `id` int NOT NULL,
  `application_id` int DEFAULT NULL,
  `investigator_given_name` varchar(255) DEFAULT NULL,
  `investigator_middle_name` varchar(255) DEFAULT NULL,
  `investigator_family_name` varchar(255) DEFAULT NULL,
  `investigator_qualification` varchar(255) DEFAULT NULL,
  `investigator_professional_address` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `protocol_outsources`
--

CREATE TABLE `protocol_outsources` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `application_id` int DEFAULT NULL,
  `outsource_id` int DEFAULT NULL,
  `owner_id` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `queries`
--

CREATE TABLE `queries` (
  `id` int NOT NULL,
  `application_id` int DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `body` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `reassignments`
--

CREATE TABLE `reassignments` (
  `id` int NOT NULL,
  `application_id` int DEFAULT NULL,
  `orig_user` int DEFAULT NULL,
  `new_user` int DEFAULT NULL,
  `assigning_user` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reminders`
--

CREATE TABLE `reminders` (
  `id` int NOT NULL,
  `foreign_key` int DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `user_id` int NOT NULL,
  `reminder_type` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reviewers`
--

CREATE TABLE `reviewers` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `application_id` int DEFAULT NULL,
  `description` text,
  `notified` tinyint(1) DEFAULT '0',
  `accepted` char(30) DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `application_id` int DEFAULT NULL,
  `category` tinyint(1) DEFAULT '0',
  `type` char(30) DEFAULT NULL,
  `assessment_type` char(30) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `text` text,
  `summary` text,
  `recommendation` text,
  `notified` tinyint DEFAULT '0',
  `accepted` char(30) DEFAULT NULL,
  `conflict` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Unsubmitted',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `review_answers`
--

CREATE TABLE `review_answers` (
  `id` int NOT NULL,
  `review_id` int NOT NULL,
  `question_type` varchar(100) DEFAULT NULL,
  `review_type` varchar(50) DEFAULT NULL,
  `question_number` decimal(11,2) DEFAULT NULL,
  `question` text,
  `answer` text,
  `workspace` text,
  `comment` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `review_questions`
--

CREATE TABLE `review_questions` (
  `id` int NOT NULL,
  `question_number` decimal(11,2) DEFAULT NULL,
  `question` text NOT NULL,
  `question_type` varchar(100) NOT NULL,
  `review_type` varchar(50) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `id` int NOT NULL,
  `value` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `icsr_code` varchar(100) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `routes`
--

INSERT INTO `routes` (`id`, `value`, `name`, `icsr_code`, `created`, `modified`) VALUES
(2, 'oral', 'oral', '048', NULL, '2020-02-28 12:51:06'),
(3, 'intravenous drip', 'intravenous drip', '041', NULL, '2020-08-13 10:22:37'),
(4, 'intravenous bolus', 'intravenous bolus', '040', NULL, '2021-05-21 09:06:30'),
(5, 'subcutaneous', 'subcutaneous', '058', NULL, '2020-08-27 09:12:24'),
(6, 'nasal', 'nasal', '045', NULL, '2020-08-13 10:22:37'),
(7, 'sublingual', 'sublingual', '060', NULL, NULL),
(8, 'topical', 'topical', '061', NULL, '2021-11-15 17:09:46'),
(9, 'rectal', 'rectal', '054', NULL, '2022-07-04 16:53:56'),
(10, 'intra-articular', 'intra-articular', '014', NULL, NULL),
(11, 'intrathecal', 'intrathecal', '037', NULL, NULL),
(12, 'intra-arterial', 'intra-arterial', '013', NULL, NULL),
(13, 'auricular (otic)', 'auricular (otic)', '001', NULL, '2024-06-17 14:47:43'),
(14, 'buccal', 'buccal', '002', NULL, NULL),
(15, 'cutaneous', 'cutaneous', '003', NULL, '2022-06-06 11:10:50'),
(16, 'dental', 'dental', '004', NULL, NULL),
(17, 'endocervical', 'endocervical', '005', NULL, NULL),
(18, 'endosinusial', 'endosinusial', '006', NULL, '2019-05-22 22:26:16'),
(19, 'endotracheal', 'endotracheal', '007', NULL, '2019-05-22 22:26:16'),
(20, 'epidural', 'epidural', '008', NULL, '2024-06-17 14:47:43'),
(21, 'extra-amniotic', 'extra-amniotic', '009', NULL, NULL),
(22, 'hemodialysis', 'hemodialysis', '010', NULL, NULL),
(23, 'intra corpus cavernosum', 'intra corpus cavernosum', '011', NULL, NULL),
(24, 'intra-amniotic', 'intra-amniotic', '012', NULL, NULL),
(25, 'intracardiac', 'intracardiac', '016', NULL, NULL),
(26, 'intracavernous', 'intracavernous', '017', NULL, NULL),
(27, 'intracerebral', 'intracerebral', '018', NULL, NULL),
(28, 'intracervical', 'intracervical', '019', NULL, NULL),
(29, 'intracisternal', 'intracisternal', '020', NULL, NULL),
(30, 'intracorneal', 'intracorneal', '021', NULL, NULL),
(31, 'intracoronary', 'intracoronary', '022', NULL, NULL),
(32, 'intradermal', 'intradermal', '023', NULL, NULL),
(33, 'intradiscal (intraspinal)', 'intradiscal (intraspinal)', '024', NULL, NULL),
(34, 'intrahepatic', 'intrahepatic', '025', NULL, '2024-06-17 14:47:43'),
(35, 'intralesional', 'intralesional', '026', NULL, NULL),
(36, 'intralymphatic', 'intralymphatic', '027', NULL, NULL),
(37, 'intramedullar (bone marrow)', 'intramedullar (bone marrow)', '028', NULL, NULL),
(38, 'intrameningeal', 'intrameningeal', '029', NULL, NULL),
(39, 'intramuscular', 'intramuscular', '030', NULL, '2020-02-06 10:00:56'),
(40, 'intraocular', 'intraocular', '031', NULL, '2022-06-17 16:53:12'),
(41, 'intrapericardial', 'intrapericardial', '032', NULL, NULL),
(42, 'intraperitoneal', 'intraperitoneal', '033', NULL, NULL),
(43, 'intrapleural', 'intrapleural', '034', NULL, '2024-06-17 14:47:43'),
(44, 'intrasynovial', 'intrasynovial', '035', NULL, NULL),
(45, 'intrathoracic', 'intrathoracic', '038', NULL, NULL),
(46, 'intratracheal', 'intratracheal', '039', NULL, '2026-04-17 09:49:02'),
(47, 'intratumor', 'intratumor', '036', NULL, NULL),
(48, 'intra-uterine', 'intra-uterine', '015', NULL, NULL),
(49, 'intravenous (nos)', 'intravenous (nos)', '042', NULL, '2021-07-29 14:50:16'),
(50, 'intravesical', 'intravesical', '043', NULL, NULL),
(51, 'iontophoresis', 'iontophoresis', '044', NULL, NULL),
(52, 'occlusive dressing technique', 'occlusive dressing technique', '046', NULL, NULL),
(53, 'ophthalmic', 'ophthalmic', '047', NULL, '2021-08-10 11:29:27'),
(54, 'oropharingeal', 'oropharingeal', '049', NULL, NULL),
(55, 'other', 'other', '050', NULL, '2020-02-10 06:53:35'),
(56, 'parenteral', 'parenteral', '051', NULL, '2022-07-15 09:07:27'),
(57, 'periarticular', 'periarticular', '052', NULL, NULL),
(58, 'perineural', 'perineural', '053', NULL, NULL),
(59, 'respiratory (inhalation)', 'respiratory (inhalation)', '055', NULL, '2022-02-24 08:25:45'),
(60, 'retrobulbar', 'retrobulbar', '056', NULL, NULL),
(61, 'subdermal', 'subdermal', '059', NULL, NULL),
(62, 'sunconjunctival', 'sunconjunctival', '057', NULL, NULL),
(63, 'transdermal', 'transdermal', '062', NULL, '2023-03-09 10:52:42'),
(64, 'transmammary', 'transmammary', '063', NULL, NULL),
(65, 'transplacental', 'transplacental', '064', NULL, NULL),
(66, 'unknown', 'unknown', '065', NULL, '2022-02-12 12:30:18'),
(67, 'urethral', 'urethral', '066', NULL, NULL),
(68, 'vaginal', 'vaginal', '067', NULL, '2022-10-25 09:06:36');

-- --------------------------------------------------------

--
-- Table structure for table `saes`
--

CREATE TABLE `saes` (
  `id` int NOT NULL,
  `application_id` int DEFAULT NULL,
  `sae_id` int DEFAULT NULL,
  `reference_no` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `form_type` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `patient_initials` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `country_id` int DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `age_years` int DEFAULT NULL,
  `gender` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `causality` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `enrollment_date` date DEFAULT NULL,
  `administration_date` date DEFAULT NULL,
  `latest_date` date DEFAULT NULL,
  `reaction_onset` date DEFAULT NULL,
  `reaction_end_date` date DEFAULT NULL,
  `patient_died` tinyint(1) DEFAULT '0',
  `prolonged_hospitalization` tinyint(1) DEFAULT '0',
  `incapacity` tinyint(1) DEFAULT '0',
  `life_threatening` tinyint(1) DEFAULT '0',
  `reaction_other` tinyint(1) DEFAULT '0',
  `reaction_description` text,
  `relevant_history` text,
  `manufacturer_name` varchar(255) DEFAULT NULL,
  `mfr_no` varchar(255) DEFAULT NULL,
  `manufacturer_date` date DEFAULT NULL,
  `source_study` tinyint(1) DEFAULT '0',
  `source_literature` tinyint(1) DEFAULT '0',
  `source_health_professional` tinyint(1) DEFAULT '0',
  `report_date` date DEFAULT NULL,
  `report_type` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT 'Initial',
  `approved` tinyint DEFAULT '0',
  `date_submitted` datetime DEFAULT NULL,
  `approved_by` int DEFAULT NULL,
  `email_address` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `reporter_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `reporter_phone` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `reporter_email` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `sae_dates`
--

CREATE TABLE `sae_dates` (
  `id` int NOT NULL,
  `sae_id` int DEFAULT NULL,
  `date` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `safety_reports`
--

CREATE TABLE `safety_reports` (
  `id` int NOT NULL,
  `application_id` int DEFAULT NULL,
  `safety_type` varchar(255) DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `title` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `site_answers`
--

CREATE TABLE `site_answers` (
  `id` int NOT NULL,
  `site_inspection_id` int NOT NULL,
  `question_type` varchar(100) DEFAULT NULL,
  `question_number` int DEFAULT NULL,
  `question` text,
  `answer` text,
  `comment` text,
  `finding` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `site_details`
--

CREATE TABLE `site_details` (
  `id` int NOT NULL,
  `application_id` int DEFAULT NULL,
  `county_id` int DEFAULT NULL,
  `site_name` varchar(255) DEFAULT NULL,
  `physical_address` varchar(255) DEFAULT NULL,
  `contact_details` varchar(255) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `site_capacity` varchar(30) DEFAULT NULL,
  `misc` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `site_inspections`
--

CREATE TABLE `site_inspections` (
  `id` int NOT NULL,
  `application_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `reference_no` varchar(55) DEFAULT NULL,
  `study_title` text,
  `protocol_no` varchar(255) DEFAULT NULL,
  `version_no` varchar(255) DEFAULT NULL,
  `pactr_number` varchar(255) DEFAULT NULL,
  `trial_phase` varchar(255) DEFAULT NULL,
  `investigators` text,
  `co_investigators` text,
  `study_stage` varchar(255) DEFAULT NULL,
  `inspection_country` text,
  `inspector_names` text,
  `inspection_dates` text,
  `site_address` text,
  `sponsor_address` text,
  `lab_address` text,
  `events_summary` text,
  `conclusion` text,
  `summary_report` text,
  `outcome` varchar(255) DEFAULT NULL,
  `approved` tinyint DEFAULT '0',
  `summary_approved` tinyint DEFAULT '0',
  `approved_by` int DEFAULT NULL,
  `sent_to_pi` tinyint NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `site_questions`
--

CREATE TABLE `site_questions` (
  `id` int NOT NULL,
  `question_number` decimal(11,1) DEFAULT NULL,
  `question` varchar(800) NOT NULL,
  `question_type` varchar(100) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `site_questions`
--

INSERT INTO `site_questions` (`id`, `question_number`, `question`, `question_type`, `created`, `modified`) VALUES
(1, NULL, 'Data Integrity', 'section', '2019-04-24 22:44:39', '2019-04-24 22:48:50'),
(2, '1.0', 'There is a written data integrity policy..', 'question', '2019-04-24 22:46:50', '2019-04-25 10:20:55'),
(3, '2.0', 'There is an SOP describing principles of data integrity ensuring ALCOA', 'question', '2019-04-24 22:47:13', '2019-04-24 22:47:13'),
(4, '3.0', 'Data and results were reviewed and considered complying to data integrity requirements. (If â€œnoâ€, complete comments section below)', 'question', '2019-04-24 22:47:40', '2019-04-24 22:47:40'),
(5, NULL, 'Data integrity comment', 'comment', '2019-04-25 06:16:10', '2019-04-25 06:16:10'),
(6, NULL, 'Data integrity findings', 'finding', '2019-04-25 06:16:32', '2019-04-25 06:16:32'),
(7, NULL, 'Protocol', 'section', '2019-04-25 06:16:51', '2019-04-25 06:16:51'),
(8, '4.0', 'The correct version of the protocol, approved by the ethics  committee and regulatory authority, was used. ', 'question', '2019-04-25 06:19:09', '2019-04-25 06:19:09'),
(9, '5.0', 'The protocol included inclusion and exclusion criteria, reference to randomization, investigational product and other required information', 'question', '2019-04-25 06:58:22', '2019-04-25 06:58:22'),
(10, '6.0', 'Meals, dosing, and sample collection were included in the  protocol.', 'question', '2019-04-25 06:58:54', '2019-04-25 06:58:54'),
(11, '7.0', ' Deviations and violations from the protocol were recorded and  justified.', 'question', '2019-04-25 06:59:16', '2019-04-25 06:59:16'),
(12, '8.0', ' Amendments to the protocol were approved by the ethics  committee and regulatory authority.', 'question', '2019-04-25 06:59:35', '2019-04-25 06:59:35'),
(13, '9.0', 'The investigators signed confirmation to conduct the trial according to the protocol and Good Clinical Practice was available.', 'question', '2019-04-25 07:00:06', '2019-04-25 07:00:06'),
(14, NULL, 'Protocol comments and remarks: ', 'comment', '2019-04-25 07:01:20', '2019-04-25 07:01:20'),
(15, NULL, 'Protocol findings', 'finding', '2019-04-25 07:02:00', '2019-04-25 07:02:32'),
(16, NULL, 'Ethics approval ', 'section', '2019-04-25 07:02:51', '2019-04-25 07:02:51'),
(17, '10.0', 'The composition of the ethics committee (EC) is in compliance  with national requirements. ', 'question', '2019-04-25 07:03:17', '2019-04-25 07:03:17'),
(18, '11.0', 'The EC members are free from bias in relation to the clinical trial  and sponsor.', 'question', '2019-04-25 07:03:41', '2019-04-25 07:03:41'),
(19, '12.0', 'The EC operates according to SOPs. ', 'question', '2019-04-25 07:04:12', '2019-04-25 07:04:12'),
(20, '13.0', 'Approval for the clinical trial was given prior to the start of the  trial.', 'question', '2019-04-25 07:04:34', '2019-04-25 07:04:34'),
(21, '14.0', ' All relevant documents (e.g. recruitment, consent forms, protocol)  were approved by the EC.', 'question', '2019-04-25 07:04:59', '2019-04-25 07:04:59'),
(22, '15.0', ' Reports, including reports of serious adverse events, were  submitted to the EC as required. ', 'question', '2019-04-25 07:05:32', '2019-04-25 07:05:32'),
(23, NULL, 'Ethics approval Comments and Remarks ', 'comment', '2019-04-25 07:08:48', '2019-04-25 07:08:48'),
(24, NULL, 'Ethics approval Findings', 'finding', '2019-04-25 07:09:19', '2019-04-25 07:09:19'),
(25, NULL, 'Regulatory approval ', 'section', '2019-04-25 07:09:48', '2019-04-25 07:09:48'),
(26, '16.0', 'Approval for the conduct of the trial was granted in writing before  the start of the trial.', 'question', '2019-04-25 07:10:30', '2019-04-25 07:10:30'),
(27, '17.0', 'Revisions and changes to the protocol and related documents  were granted approval prior to their implementation.', 'question', '2019-04-25 07:10:53', '2019-04-25 07:10:53'),
(28, '18.0', ' Serious adverse events and other reports were submitted to the  NRA as required. ', 'question', '2019-04-25 07:11:32', '2019-04-25 07:11:32'),
(29, NULL, 'Regulatory approval  comments and remarks', 'comment', '2019-04-25 07:16:27', '2019-04-25 07:16:27'),
(30, NULL, 'Regulatory approval findings', 'finding', '2019-04-25 07:16:47', '2019-04-25 07:16:47'),
(31, NULL, 'Site inspection ', 'section', '2019-04-25 07:17:11', '2019-04-25 07:17:11'),
(32, '19.0', 'The site was licensed or otherwise authorized for the conduct of  clinical trials.', 'question', '2019-04-25 07:17:30', '2019-04-25 07:17:30'),
(33, '20.0', 'The site was suitable for the conduct of clinical trials, and had appropriate areas for the different activities as required in the trial. ', 'question', '2019-04-25 07:17:55', '2019-04-25 07:17:55'),
(34, '21.0', ' Access was controlled.', 'question', '2019-04-25 07:18:26', '2019-04-25 07:18:26'),
(35, NULL, 'Site inspection  comments and remarks', 'comment', '2019-04-25 07:19:23', '2019-04-25 07:19:23'),
(36, NULL, 'Site inspection findings', 'finding', '2019-04-25 07:21:23', '2019-04-25 07:21:23'),
(37, NULL, 'Clinic', 'section', '2019-04-25 07:27:31', '2019-04-25 07:27:31'),
(38, '22.0', 'The clinic had required areas such as registration, screening, beds  for hosting, dosing, sample collection.', 'question', '2019-04-25 07:32:09', '2019-04-25 07:32:09'),
(39, '23.0', 'There was a suitably equipped emergency area with required  emergency medication.', 'question', '2019-04-25 07:32:28', '2019-04-25 07:32:28'),
(40, '24.0', ' Emergency medication was within their shelf life, and emergency  equipment was suitable for use.', 'question', '2019-04-25 07:32:46', '2019-04-25 07:32:46'),
(41, '25.0', 'Toilet and washing facilities were available.', 'question', '2019-04-25 07:33:09', '2019-04-25 07:33:09'),
(42, NULL, 'Clinic comments and remarks: ', 'comment', '2019-04-25 07:33:37', '2019-04-25 07:33:37'),
(43, NULL, 'Clinic findings', 'finding', '2019-04-25 07:33:54', '2019-04-25 07:34:14'),
(44, NULL, 'Pharmacy', 'section', '2019-04-25 07:34:31', '2019-04-25 07:34:31'),
(45, '26.0', ' Access to the pharmacy was controlled and logs were maintained  for the entry and exit.', 'question', '2019-04-25 07:34:51', '2019-04-25 07:34:51'),
(46, '27.0', ' SOPs were detailed and described the different activities in the  pharmacy.', 'question', '2019-04-25 07:35:11', '2019-04-25 07:35:11'),
(47, '28.0', 'Storage conditions were appropriate, as required for the storage of the products. Records were maintained. No excursions were noted.', 'question', '2019-04-25 07:35:31', '2019-04-25 07:35:31'),
(48, '29.0', 'Where storage conditions were out of limit, these were investigated and appropriate corrective and preventive actions (CAPAs) were taken.', 'question', '2019-04-25 07:35:55', '2019-04-25 07:35:55'),
(49, '30.0', ' Records relating to the IMP, such as import license or import authorization, proof of purchase, shipping letter, storage conditions during transport, receipt at the site, COA(s), stock card and dispensing record were in place. ', 'question', '2019-04-25 07:36:11', '2019-04-25 07:36:11'),
(50, '31.0', ' Dispensing was done according to an SOP and randomization,  with no risks of mix ups. ', 'question', '2019-04-25 07:36:23', '2019-04-25 07:36:23'),
(51, '32.0', 'Investigational products were appropriately labelled. ', 'question', '2019-04-25 07:36:42', '2019-04-25 07:36:42'),
(52, '33.0', ' IMP labels contained the correct information.', 'question', '2019-04-25 07:36:58', '2019-04-25 07:36:58'),
(53, '34.0', 'Dosing (or administration) was done according to the  randomization sheet and protocol; and indicated in the CRF.', 'question', '2019-04-25 07:37:16', '2019-04-25 07:37:16'),
(54, '35.0', ' IMP accountability was verified and found correct.', 'question', '2019-04-25 07:37:31', '2019-04-25 07:37:31'),
(55, '36.0', 'An SOP for safe disposal of waste was followed.', 'question', '2019-04-25 07:37:49', '2019-04-25 07:37:49'),
(56, NULL, 'Pharmacy comments and remarks', 'comment', '2019-04-25 07:38:43', '2019-04-25 07:38:43'),
(57, NULL, 'Pharmacy findings', 'finding', '2019-04-25 07:38:56', '2019-04-25 07:39:21'),
(58, NULL, 'Documentation ', 'section', '2019-04-25 07:39:46', '2019-04-25 07:39:46'),
(59, '37.0', 'The trial site operated in accordance with a documented quality  management system.', 'question', '2019-04-25 07:48:58', '2019-04-25 07:48:58'),
(60, '38.0', ' Policies, procedures, and responsibilities were documented and  followed.', 'question', '2019-04-25 07:49:19', '2019-04-25 07:49:19'),
(61, '39.0', 'The quality system covered at least management of deviations, violations, risk management principles and Corrective and Preventive Actions (CAPA).', 'question', '2019-04-25 07:49:36', '2019-04-25 07:49:36'),
(62, '40.0', 'Curriculum vitae of key personnel were current.', 'question', '2019-04-25 07:50:43', '2019-04-25 07:50:43'),
(63, '41.0', 'An SOP and records for qualification and training of employees  and contracted personnel were available.', 'question', '2019-04-25 07:50:59', '2019-04-25 07:50:59'),
(64, NULL, 'Documentation comments and remarks', 'comment', '2019-04-25 07:51:39', '2019-04-25 07:51:39'),
(65, NULL, 'Documentation findings', 'finding', '2019-04-25 07:51:54', '2019-04-25 07:51:54'),
(66, NULL, 'Contracts ', 'section', '2019-04-25 07:52:47', '2019-04-25 07:52:47'),
(67, '42.0', 'A current, valid contract existed between the Sponsor and the  investigator', 'question', '2019-04-25 07:53:31', '2019-04-25 07:53:31'),
(68, '43.0', 'Responsibilities for each party were clearly described and included e.g. IPs; monitoring of the trial, quality assurance, reports, insurance.', 'question', '2019-04-25 07:53:48', '2019-04-25 07:53:48'),
(69, '44.0', 'Contracts with outsourced personnel, laboratories and other  service providers were in place. ', 'question', '2019-04-25 07:54:35', '2019-04-25 07:54:35'),
(70, NULL, 'Contracts comments and remarks', 'comment', '2019-04-25 07:55:01', '2019-04-25 07:55:01'),
(71, NULL, 'Contracts findings', 'question', '2019-04-25 07:55:21', '2019-04-25 07:55:21'),
(72, NULL, 'Archive ', 'section', '2019-04-25 07:55:49', '2019-04-25 07:55:49'),
(73, '45.0', 'An archiving area was available. There was sufficient space, records were protected from damage such as fire, water, humidity and deterioration.', 'question', '2019-04-25 07:56:12', '2019-04-25 07:56:12'),
(74, '46.0', ' Procedures and records were available for the placement and retrieval of documents and trial data (hard copies and electronic data). ', 'question', '2019-04-25 07:56:29', '2019-04-25 07:56:29'),
(75, NULL, 'Archive comments and remarks', 'comment', '2019-04-25 07:57:05', '2019-04-25 07:57:05'),
(76, NULL, 'Archive findings', 'finding', '2019-04-25 07:57:19', '2019-04-25 07:57:19'),
(77, NULL, 'Responsibilities ', 'section', '2019-04-25 07:57:42', '2019-04-25 07:57:42'),
(78, '47.0', 'The responsibilities of the sponsor were described and met by the  sponsor.', 'question', '2019-04-25 07:58:00', '2019-04-25 07:58:00'),
(79, '48.0', 'The responsibilities of the investigator were described and met by  the investigator.', 'question', '2019-04-25 07:59:36', '2019-04-25 07:59:36'),
(80, '49.0', 'The qualifications, experience and training records of the  investigator were meeting the requirements.', 'question', '2019-04-25 07:59:52', '2019-04-25 07:59:52'),
(81, '50.0', 'The investigator signed the final report.', 'question', '2019-04-25 08:00:42', '2019-04-25 08:00:42'),
(82, '51.0', 'There was documented evidence of the delegation of tasks.', 'question', '2019-04-25 08:01:00', '2019-04-25 08:01:00'),
(83, '52.0', ' Personnel should have appropriate qualifications, experience and  training.', 'question', '2019-04-25 08:01:21', '2019-04-25 08:01:21'),
(84, '53.0', 'There was an appropriate number of employees for the conduct of  the trial.', 'question', '2019-04-25 08:01:41', '2019-04-25 08:01:41'),
(85, NULL, 'Responsibilities comments and remarks', 'comment', '2019-04-25 08:02:09', '2019-04-25 08:02:09'),
(86, NULL, 'Responsibilities findings', 'finding', '2019-04-25 08:05:42', '2019-04-25 08:05:42'),
(87, NULL, 'Monitor(s) and monitoring reports ', 'section', '2019-04-25 08:09:32', '2019-04-25 08:09:32'),
(88, '54.0', 'A monitor with appropriate experience was appointed to monitor  the study.', 'question', '2019-04-25 08:10:02', '2019-04-25 08:10:02'),
(89, '55.0', 'Monitor reports were available reflecting the site review and trial  progress.', 'question', '2019-04-25 08:10:34', '2019-04-25 08:10:34'),
(90, NULL, 'Monitor(s) and monitoring reports comments and remarks', 'comment', '2019-04-25 08:39:16', '2019-04-25 08:39:16'),
(91, NULL, 'Monitor(s) and monitoring reports findings ', 'finding', '2019-04-25 08:39:33', '2019-04-25 08:39:33'),
(92, NULL, 'Quality assurance ', 'section', '2019-04-25 08:40:03', '2019-04-25 08:40:03'),
(93, '56.0', 'Personnel responsible for the QA were independent of the trial. ', 'question', '2019-04-25 08:40:35', '2019-04-25 08:40:35'),
(94, '57.0', 'Quality Assurance reports, reflecting the review of the data and information before, during and after the conduct of the trial, were available.', 'question', '2019-04-25 08:40:49', '2019-04-25 08:40:49'),
(95, NULL, 'Quality assurance comments and remarks', 'comment', '2019-04-25 08:41:21', '2019-04-25 08:41:21'),
(96, NULL, 'Quality assurance findings', 'finding', '2019-04-25 08:41:42', '2019-04-25 08:43:30'),
(97, NULL, 'Patients and Subjects ', 'section', '2019-04-25 08:42:00', '2019-04-25 08:42:00'),
(98, '58.0', 'The trial was conducted in accordance with the principles of GCP,  the Declaration of Helsinki and CIOMS guidelines.', 'question', '2019-04-25 08:42:18', '2019-04-25 08:42:18'),
(99, '59.0', 'Subjects are not participating in more than one trial at a time, and  wash out periods are observed.', 'question', '2019-04-25 08:42:39', '2019-04-25 08:42:39'),
(100, '60.0', ' A complete record of participation in studies was available. ', 'question', '2019-04-25 08:43:12', '2019-04-25 08:43:12'),
(101, '61.0', 'Vulnerable groups were only included if justified.', 'question', '2019-04-25 08:43:55', '2019-04-25 08:43:55'),
(102, '62.0', 'Demographic data were accurately recorded.', 'question', '2019-04-25 08:44:10', '2019-04-25 08:44:10'),
(103, '63.0', 'There was justification for the number of subjects enrolled.', 'question', '2019-04-25 08:44:40', '2019-04-25 08:44:40'),
(104, '64.0', 'Signatures of subjects were cross checked and found acceptable.', 'question', '2019-04-25 08:45:09', '2019-04-25 08:45:09'),
(105, NULL, 'Patients and Subjects comments and remarks', 'comment', '2019-04-25 08:45:35', '2019-04-25 08:45:35'),
(106, NULL, 'Patients and Subjects findings', 'finding', '2019-04-25 08:45:49', '2019-04-25 08:45:49'),
(107, NULL, 'Informed Consent Forms (ICFs) ', 'section', '2019-04-25 08:46:03', '2019-04-25 08:46:03'),
(108, '65.0', 'Subjects were informed of the advantages and disadvantages of participating in a trial, about the IMP, possible adverse events, insurance and other matters.', 'question', '2019-04-25 08:46:21', '2019-04-25 08:46:21'),
(109, '66.0', 'Each subject signed the ICF prior to participating in the trial,  general (where applicable) and trial specific.', 'question', '2019-04-25 08:46:42', '2019-04-25 08:46:42'),
(110, '67.0', 'ICFs contained all the required information in a way that the  subject could understand.', 'question', '2019-04-25 08:47:05', '2019-04-25 08:47:05'),
(111, '68.0', 'The correct version of the ICF was signed. ', 'question', '2019-04-25 08:47:20', '2019-04-25 08:47:20'),
(112, '69.0', 'Contact details of PI or secretariat was given to the subjects.', 'question', '2019-04-25 08:47:30', '2019-04-25 08:47:30'),
(113, NULL, 'Informed Consent Forms (ICFs) comments and remarks', 'comment', '2019-04-25 08:47:57', '2019-04-25 08:47:57'),
(114, NULL, 'Informed Consent Forms (ICFs) findings', 'finding', '2019-04-25 08:48:15', '2019-04-25 08:48:15'),
(115, NULL, 'Randomization ', 'section', '2019-04-25 08:48:45', '2019-04-25 08:48:45'),
(116, '70.0', 'Randomization was done according to an SOP, and records were  available. ', 'question', '2019-04-25 08:49:06', '2019-04-25 08:49:06'),
(117, '71.0', 'IMPs were dispensed and dosed or administered in accordance  with the randomization schedule. ', 'question', '2019-04-25 08:49:21', '2019-04-25 08:49:21'),
(118, NULL, 'Randomization comments and remarks', 'comment', '2019-04-25 08:49:52', '2019-04-25 08:49:52'),
(119, NULL, 'Randomization findings', 'finding', '2019-04-25 08:53:38', '2019-04-25 08:53:38'),
(120, NULL, 'Case Report Forms (CRFs) ', 'section', '2019-04-25 08:58:23', '2019-04-25 08:58:23'),
(121, '72.0', 'The results and data recorded in CRFs were the same as those in  the source documents. ', 'question', '2019-04-25 08:58:38', '2019-04-25 08:58:38'),
(122, '73.0', ' Samples such as blood and urine were taken, chest X-ray or other  tests done as required. Results were within the specified ranges. ', 'question', '2019-04-25 08:58:55', '2019-04-25 08:58:55'),
(123, '74.0', 'The protocol was followed where it refers to the trial being  conducted under fasting or under fed conditions.', 'question', '2019-04-25 08:59:12', '2019-04-25 08:59:12'),
(124, '75.0', 'Meals were provided, checked, and consumption recorded.', 'question', '2019-04-25 08:59:25', '2019-04-25 08:59:25'),
(125, '76.0', 'Adverse events, concomitant medication, dosing and sample  collection were accurately recorded. ', 'question', '2019-04-25 08:59:43', '2019-04-25 08:59:43'),
(126, NULL, 'Case Report Forms (CRFs) comments and remarks', 'comment', '2019-04-25 09:00:07', '2019-04-25 09:00:07'),
(127, NULL, 'Case Report Forms (CRFs) findings', 'question', '2019-04-25 09:00:25', '2019-04-25 09:00:25'),
(128, NULL, 'Laboratories ', 'section', '2019-04-25 09:00:44', '2019-04-25 09:00:44'),
(129, '77.0', 'Laboratories were appropriately equipped to perform the required  tests. ', 'question', '2019-04-25 09:00:59', '2019-04-25 09:00:59'),
(130, '78.0', ' Where testing was outsourced, contracts were in place. ', 'question', '2019-04-25 09:01:14', '2019-04-25 09:01:14'),
(131, NULL, 'Laboratories comments and remarks', 'comment', '2019-04-25 09:01:34', '2019-04-25 09:01:34'),
(132, NULL, 'Laboratories findings', 'finding', '2019-04-25 09:01:48', '2019-04-25 09:01:48'),
(133, NULL, 'Clinical laboratory ', 'section', '2019-04-25 09:04:14', '2019-04-25 09:04:14'),
(134, '79.0', 'The laboratory followed SOPs for activities including supplier  qualification, procurement, testing.', 'question', '2019-04-25 09:04:39', '2019-04-25 09:04:39'),
(135, '80.0', 'Records were appropriate for the qualification and calibration of  the laboratory equipment and instruments.', 'question', '2019-04-25 09:05:14', '2019-04-25 09:05:14'),
(136, '81.0', 'Equipment log books were maintained.', 'question', '2019-04-25 09:05:33', '2019-04-25 09:05:33'),
(137, '82.0', 'Current normal ranges and values of the measures were specified.', 'question', '2019-04-25 09:05:48', '2019-04-25 09:05:48'),
(138, '83.0', 'Procedures were in place for the receipt, storage and handling of certified reference materials, chemicals and reagents. No expired stock was used, and storage conditions were maintained. ', 'question', '2019-04-25 09:06:02', '2019-04-25 09:06:02'),
(139, '84.0', 'Procedures were followed for handling hazardous materials e.g.  live viruses.', 'question', '2019-04-25 09:06:25', '2019-04-25 09:06:25'),
(140, '85.0', 'Test methods were verified or validated as appropriate. ', 'question', '2019-04-25 09:06:40', '2019-04-25 09:06:40'),
(141, '86.0', 'Printouts of test results complied with ALCOA principles. ', 'question', '2019-04-25 09:06:56', '2019-04-25 09:06:56'),
(142, '87.0', 'Procedures and records for the safe disposal of the laboratory  waste were available. ', 'question', '2019-04-25 09:07:15', '2019-04-25 09:07:15'),
(143, NULL, 'Clinical laboratory comments and remarks', 'question', '2019-04-25 09:08:01', '2019-04-25 09:08:01'),
(144, NULL, 'Clinical laboratory findings', 'question', '2019-04-25 09:08:25', '2019-04-25 09:08:25'),
(145, NULL, 'Bio-analytical laboratory ', 'section', '2019-04-25 09:08:45', '2019-04-25 09:08:45'),
(146, '88.0', ' The laboratory had the necessary resources to perform the  required analysis.', 'question', '2019-04-25 09:09:19', '2019-04-25 09:09:19'),
(147, '89.0', 'Areas for sample receiving and storage, sample preparation, and  analysis were suitable. ', 'question', '2019-04-25 09:09:32', '2019-04-25 09:09:32'),
(148, '90.0', 'Personnel had appropriate qualifications, experience and training.', 'question', '2019-04-25 09:09:47', '2019-04-25 09:09:47'),
(149, '91.0', 'Required equipment and instruments were qualified and  calibrated.', 'question', '2019-04-25 09:10:09', '2019-04-25 09:10:09'),
(150, '92.0', 'Source data for the trial and sample analysis were acceptable.', 'question', '2019-04-25 09:10:30', '2019-04-25 09:10:30'),
(151, NULL, 'Bio-analytical laboratory comments and remarks', 'comment', '2019-04-25 09:10:57', '2019-04-25 09:10:57'),
(152, NULL, 'Bio-analytical laboratory findings', 'finding', '2019-04-25 09:11:11', '2019-04-25 09:11:11'),
(153, NULL, 'Sample management ', 'section', '2019-04-25 09:11:27', '2019-04-25 09:11:27'),
(154, '93.0', 'Procedures and records were available for sample movement and  reconciliation was verified. ', 'question', '2019-04-25 09:11:49', '2019-04-25 09:11:49'),
(155, '94.0', 'Samples were stored at the required temperature (e.g. -20 or -70  degrees Celsius) until analysed.', 'question', '2019-04-25 09:12:06', '2019-04-25 09:12:06'),
(156, '95.0', ' Freezers used for storage of samples were qualified.', 'question', '2019-04-25 09:12:20', '2019-04-25 09:12:20'),
(157, '96.0', ' Qualification and calibration status was valid at the time of use for  method validation as well as sample analyses.', 'question', '2019-04-25 09:12:47', '2019-04-25 09:12:47'),
(158, '97.0', ' The bio-analytical method was validated before it was used to  analyse the samples. Data were inspected and found compliant.', 'question', '2019-04-25 09:13:06', '2019-04-25 09:13:06'),
(159, '98.0', 'Sample and solution stability had been established.', 'question', '2019-04-25 09:13:21', '2019-04-25 09:13:21'),
(160, '99.0', 'Reference materials used were appropriately managed, and  records were traceable. ', 'question', '2019-04-25 09:13:34', '2019-04-25 09:13:34'),
(161, NULL, 'Sample management comments and remarks', 'comment', '2019-04-25 09:14:15', '2019-04-25 09:14:28'),
(162, NULL, 'Sample management findings', 'finding', '2019-04-25 09:14:48', '2019-04-25 09:14:48'),
(163, NULL, 'Sample analysis ', 'section', '2019-04-25 09:15:01', '2019-04-25 09:15:01'),
(164, '100.0', 'Source data were accurately reported.', 'question', '2019-04-25 09:15:25', '2019-04-25 09:15:25'),
(165, '101.0', ' Instruments were in a qualified and calibrated state at the  time of sample analysis.', 'question', '2019-04-25 09:15:43', '2019-04-25 09:15:43'),
(166, '102.0', ' Electronic data were verified and met ALCOA+  principles. ', 'question', '2019-04-25 09:16:00', '2019-04-25 09:16:00'),
(167, '103.0', ' Sample sets met requirements (e.g. calibration curve,  Quality control samples)', 'question', '2019-04-25 09:16:17', '2019-04-25 09:16:17'),
(168, '104.0', ' Repeat analysis was appropriately done and in accordance  with an SOP.', 'question', '2019-04-25 09:16:29', '2019-04-25 09:16:29'),
(169, '105.0', ' Incurred Sample Analysis was done according the SOP  and the results were acceptable. ', 'question', '2019-04-25 09:16:48', '2019-04-25 09:16:48'),
(170, NULL, 'Sample analysis comments and remarks', 'question', '2019-04-25 09:19:24', '2019-04-25 09:19:24'),
(171, NULL, 'Sample analysis findings', 'finding', '2019-04-25 09:19:38', '2019-04-25 09:19:38'),
(172, NULL, 'Statistical analysis ', 'section', '2019-04-25 09:19:57', '2019-04-25 09:19:57'),
(173, '106.0', 'Statistical analysis of data was reviewed and found  acceptable.', 'question', '2019-04-25 09:20:33', '2019-04-25 09:20:33'),
(174, NULL, 'Statistical analysis comments and remarks', 'comment', '2019-04-25 09:20:55', '2019-04-25 09:20:55'),
(175, NULL, 'Statistical analysis findings', 'finding', '2019-04-25 09:21:10', '2019-04-25 09:21:10'),
(176, NULL, 'Study report ', 'section', '2019-04-25 09:23:04', '2019-04-25 09:23:04'),
(177, '107.0', 'The final report was a true reflection of study and was in a  suitable format (e.g. as per ICH guidelines). ', 'question', '2019-04-25 09:23:21', '2019-04-25 09:23:21'),
(178, '108.0', 'The report was signed and dated by responsible persons  including the investigator. ', 'question', '2019-04-25 09:23:38', '2019-04-25 09:23:38'),
(179, NULL, 'Study report comments and remarks', 'comment', '2019-04-25 09:24:00', '2019-04-25 09:24:00'),
(180, NULL, 'Study report findings', 'finding', '2019-04-25 09:24:22', '2019-04-25 09:24:22'),
(181, NULL, 'Multicentre trial ', 'section', '2019-04-25 09:24:39', '2019-04-25 09:24:39'),
(182, '109.0', 'The points above were checked for multicentre trials. ', 'question', '2019-04-25 09:25:05', '2019-04-25 09:25:05'),
(183, '110.0', 'There was poof of written acceptance of the protocol and  its annexes by all investigators.', 'question', '2019-04-25 09:25:15', '2019-04-25 09:25:15'),
(184, '111.0', 'Records were available for the meetings between parties.', 'question', '2019-04-25 09:25:29', '2019-04-25 09:25:29'),
(185, '112.0', 'Procedures were available addressing centralized data  management and analysis. ', 'question', '2019-04-25 09:25:47', '2019-04-25 09:25:47'),
(186, '113.0', 'Safety reports were provided to investigators from all sites  involved in a multicenter trial. ', 'question', '2019-04-25 09:25:59', '2019-04-25 09:25:59'),
(187, NULL, 'Multicentre trial comments and remarks', 'comment', '2019-04-25 09:26:55', '2019-04-25 09:26:55'),
(188, NULL, 'Multicentre trial findings', 'finding', '2019-04-25 09:27:07', '2019-04-25 09:27:07'),
(189, NULL, 'Any other general comment or remark: ', 'general_comment', '2019-04-25 09:27:24', '2019-04-25 09:27:24');

-- --------------------------------------------------------

--
-- Table structure for table `sponsors`
--

CREATE TABLE `sponsors` (
  `id` int NOT NULL,
  `application_id` int DEFAULT NULL,
  `sponsor` varchar(255) DEFAULT NULL,
  `sponsor_type` varchar(100) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `telephone_number` varchar(255) DEFAULT NULL,
  `fax_number` varchar(255) DEFAULT NULL,
  `cell_number` varchar(255) DEFAULT NULL,
  `email_address` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sponsors`
--

INSERT INTO `sponsors` (`id`, `application_id`, `sponsor`, `sponsor_type`, `contact_person`, `address`, `telephone_number`, `fax_number`, `cell_number`, `email_address`, `created`, `modified`) VALUES
(3, 4, 'National Institutes of Health', 'Others', '', '9000 Rockville Pike Bethesda, Maryland 20892', '301-496-4000', '', '301-496-4000', 'NIHinfo@od.nih.gov', '2024-01-11 11:46:03', '2024-01-18 15:27:00'),
(4, 6, 'Sponsor Name', 'Research Institution', 'Test Contact Person', 'Some Address', '0700543987', '', '0700432321', 'sponsor@gmail.com', '2024-01-17 10:25:00', '2024-05-20 19:34:49'),
(5, 8, 'test', 'Local Kenyan Investigator', 'test', 'test adress', '0783910378', '', '0783901535', 'test@gmail.com', '2024-01-18 13:02:36', '2024-01-18 13:52:45'),
(6, 9, 'Top Defender', 'Industry', '', '378, Kisumu', '+254707829176', '', '+54719028366', 'topdefender@gmail.com', '2024-01-23 10:30:49', '2024-01-24 12:50:04'),
(7, 11, 'anc', 'Research Institution', '', '3221', '0712000000', '', '0712000000', 'skerama@ppb.go.ke', '2024-02-09 05:55:16', '2024-05-31 16:12:58'),
(8, 7, 'sdsdd', 'Industry', '', 'ddddd', '0700443322', '', '0700443322', 'sample@gmail.com', '2024-02-22 01:39:23', '2024-02-22 02:11:48'),
(9, 13, 'Sponsor', 'Research Institution', '', 'Chuka Uni', '0712367816', '', '0745617653', 'sponsor@gmail.com', '2024-03-14 09:09:17', '2024-03-26 06:43:11'),
(10, 14, 'Sponsor', 'Industry', '', 'Address', '+254790192873', '', '+254718290192', 'email@ac.ke', '2024-04-09 08:47:06', '2024-04-09 09:34:57'),
(11, 20, 'May Protocol Manufacturer Sponsor', 'Research Institution', '', 'May Protocol Manufacturer Address', '+2547901923783', '', '+25478901928', 'manuf@gmail.com', '2024-05-21 16:23:42', '2024-05-21 17:01:32'),
(12, 21, '', '', '', '', '', '', '', '', '2024-05-21 16:26:12', '2024-05-21 16:26:12'),
(13, 19, '', '', '', '', '', '', '', '', '2024-05-21 16:28:53', '2024-05-21 16:28:53'),
(14, 2, 'some sponsor', 'Industry', 'some contact person', 'address', '0700432422', '', '0700434343', 'sponsor@gmail.com', '2024-05-22 16:52:09', '2024-06-25 08:22:28'),
(15, 40, '', '', '', '', '', '', '', '', '2024-05-23 14:28:00', '2024-05-23 14:31:28'),
(16, 41, 'Quod sunt eligendi illum ab perferendis officia nihil et quia in nulla dolor', 'Others', 'Inventore labore veniam in numquam laborum Magnam quos beatae qui natus', 'Saepe commodo culpa omnis aspernatur proident et', '+1 (248) 754-8124', '+1 (533) 843-5666', '+1 (647) 568-5631', 'lojocip@mailinator.com', '2024-05-24 11:00:45', '2024-05-24 15:13:22'),
(17, 43, 'ABC Pharmaceuticals Ltd.', 'Industry', 'John Smith', ' 789 Oak Avenue, Nairobi, Kenya', '+254-20-987-6543', '+254-20-987-6544', '+254-712-345-678', 'john.smith@abcpharma.co.ke', '2024-05-24 11:52:46', '2024-05-24 12:58:04'),
(18, 86, 'GenoPharmaceuticals Inc.', 'Industry', 'Mr. John Smith', '789 Pharma Avenue, MedTech City, State, ZIP', '+1 (800) 555-1234', '+1 (800) 555-5678', '+1 (800) 555-9012', 'info@genopharmaceuticals.com', '2024-06-11 04:05:15', '2024-06-11 09:01:24'),
(19, 77, 'Voluptas architecto sapiente corporis rem nihil officiis itaque', 'Local Kenyan Investigator', 'Quo ipsam labore quis ad necessitatibus rerum explicabo Rerum fuga Nam ratione et duis nihil error totam doloribus', 'Provident irure explicabo Duis nihil enim totam ut', '0700432876', '0700321876', '0700343232', 'hegito@mailinator.com', '2024-06-11 06:27:44', '2024-06-11 06:48:58'),
(20, 90, 'Genomica Pharma Inc.', 'Industry', 'Dr. Alice Njeri', '123 Innovation Drive, Biotech Park, Nairobi, Kenya', '+254-20-123-4567', '+254-20-765-4321', '+254-722-123456', 'alice.njeri@genomicapharma.com', '2024-06-18 05:47:10', '2024-06-19 08:52:01'),
(21, 95, '', '', '', '', '', '', '', '', '2024-06-20 09:48:56', '2024-06-20 09:48:56'),
(22, 99, 'BioPharma Innovations Ltd.', 'Industry', '', 'BioPharma Innovations Ltd. 123 Main Street San Francisco, CA 94105 United States', '+1 415-555-6789', '', '+1 415-555-6778', 'info@biopharmainnovations.com', '2024-06-20 10:55:03', '2024-11-15 11:23:43'),
(23, 98, 'BioPharma Innovations Ltd.', 'Industry', '', 'BioPharma Innovations Ltd. 123 Main Street San Francisco, CA 94105 United States', '+1 415-555-6789', '', '+1 415-555-6778', 'info@biopharmainnovations.com', '2024-06-20 10:55:30', '2024-06-25 08:51:45'),
(24, 101, '', '', '', '', '', '', '', '', '2024-07-16 09:30:16', '2024-07-16 09:30:16'),
(25, 104, '', '', '', '', '', '', '', '', '2024-08-02 12:14:03', '2024-08-02 12:23:01'),
(26, 102, '', '', '', '', '', '', '', '', '2024-08-09 08:40:05', '2024-08-09 08:40:05'),
(27, 110, '', '', '', '', '', '', '', '', '2024-08-10 09:09:14', '2024-08-10 10:54:17'),
(28, 117, 'Provident iure accusamus totam harum voluptatem', 'Local Kenyan Investigator', 'Laudantium ut cumque aut tempor mollitia ut nemo est magna placeat commodi', 'Error ut blanditiis incidunt doloremque commodo quos adipisci autem', '+1 (533) 337-9361', '+1 (555) 186-3019', '+1 (187) 572-7586', 'qiniqi@mailinator.com', '2024-10-16 15:52:09', '2024-10-16 16:49:00'),
(29, 120, 'Fuga Aut qui est consequatur consectetur dolorem qui blanditiis saepe natus maiores ea repellendus Velit quia ullamco', 'Research Institution', 'Non corrupti reiciendis placeat elit aut voluptatibus optio labore excepteur', 'Consectetur ut ipsum quia quos magni', '+1 (367) 517-6326', '+1 (961) 772-5694', '+1 (895) 885-1686', 'rimumaxy@mailinator.com', '2024-10-17 12:49:37', '2024-10-23 15:04:34'),
(30, 123, '', '', '', '', '', '', '', '', '2024-11-18 12:21:44', '2024-11-18 12:23:10'),
(31, 124, 'Sponsor', 'Industry', '', 'Address', '+254782930397', '', '+254782930397', 'slow@gmail.com', '2024-11-20 09:12:27', '2025-01-14 12:21:23'),
(32, 121, 'Anim obcaecati dolorem cillum qui nemo in vitae ea veritatis', 'Industry', 'Fugiat corporis inventore non commodo nesciunt ut non doloremque optio voluptates minima recusandae Veritatis reprehenderit velit commodo', 'Autem odio eaque quia est blanditiis reiciendis commodi et adipisicing enim voluptate', '+1 (343) 625-7251', '+1 (936) 324-2944', '+1 (942) 698-1737', 'vugit@mailinator.com', '2024-11-20 09:47:01', '2024-11-20 10:15:06'),
(33, 125, 'Dolore sunt reprehenderit voluptas commodi asperiores', 'Industry', 'Ad consequatur nulla aut quo explicabo Eligendi similique ex cum sed voluptas sint sit dolorem nulla asperiores suscipit', 'Quis ipsa voluptatum non officia nisi minus officia est et', '+1 (498) 749-6935', '+1 (547) 888-9241', '+1 (941) 425-9332', 'cidesy@mailinator.com', '2024-12-17 08:32:23', '2025-01-06 11:48:30'),
(34, 127, 'Fugit nulla amet numquam veniam qui minim ratione ducimus voluptatem Eos molestiae obcaecati est repudiandae aut id Nam enim ut', 'Research Institution', 'Quasi voluptas voluptate veritatis quis', 'Voluptatem Sit eum voluptas nobis aliqua Nostrud minus officia voluptatum eveniet vel lorem aperiam cupidatat', '+1 (924) 838-9502', '+1 (461) 834-7154', '+1 (976) 269-8435', 'gopokilos@mailinator.com', '2025-01-06 15:08:18', '2025-02-06 14:33:08'),
(35, 128, 'Aut voluptatum est ab voluptas tempore aut et voluptas dolore architecto ad suscipit saepe omnis quaerat', 'Local Kenyan Investigator', 'Quas ut consectetur ratione est tempor quo odio iure esse occaecat eius suscipit explicabo Quasi sit', 'Excepteur distinctio Enim vitae reiciendis aut aliquid incididunt repudiandae nesciunt beatae adipisci', '+1 (731) 766-1698', '+1 (493) 753-7389', '+1 (179) 791-1499', 'covoginoby@mailinator.com', '2025-01-07 09:33:40', '2025-02-17 10:51:23'),
(36, 118, 'Enim sint voluptatem eos nobis reiciendis dolore et qui corrupti quod minim pariatur Possimus', 'Local Kenyan Investigator', 'Dolorem mollitia id quis provident error voluptatem voluptate', 'Dolore laborum delectus dolor corrupti rerum aut magnam impedit quia cillum et cupiditate aliquid sapiente', '+1 (666) 325-1183', '+1 (314) 248-5575', '+1 (134) 102-6236', 'tuhokelyh@mailinator.com', '2025-01-13 12:13:08', '2025-02-06 10:49:58'),
(37, 140, 'Modi officia necessitatibus qui qui fuga Dolore eum eligendi', 'Others', 'Ullamco aspernatur dolor incididunt in dolore molestiae necessitatibus hic assumenda expedita explicabo Aliquid voluptas sunt explicabo Odit id maxime aliquid', 'Quidem rerum odio ipsum quia obcaecati saepe voluptatum in eos aut debitis quidem iusto', '+1 (777) 763-4616', '+1 (521) 133-9772', '+1 (367) 935-5004', 'megekof@mailinator.com', '2025-01-14 15:08:21', '2025-01-23 08:38:50'),
(38, 155, 'Laboris minima dolorem duis voluptatibus', 'Research Institution', 'Consectetur velit a ullam est deleniti fugiat fugiat ipsam', 'Aut reprehenderit vero sunt eum praesentium facere aute dolores ad explicabo Lorem commodo nesciunt fuga Dolore sequi sed voluptate quibusdam', '+1 (184) 898-4471', '+1 (974) 415-9275', '+1 (231) 995-6436', 'kureqog@mailinator.com', '2025-01-21 15:59:50', '2025-01-21 16:02:05'),
(39, 158, 'Enim sint voluptatem eos nobis reiciendis dolore et qui corrupti quod minim pariatur Possimus', 'Local Kenyan Investigator', 'Dolorem mollitia id quis provident error voluptatem voluptate', 'Dolore laborum delectus dolor corrupti rerum aut magnam impedit quia cillum et cupiditate aliquid sapiente', '+1 (666) 325-1183', '+1 (314) 248-5575', '+1 (134) 102-6236', 'tuhokelyh@mailinator.com', '2025-01-13 12:13:08', '2025-02-13 08:07:31'),
(40, 159, 'Enim sint voluptatem eos nobis reiciendis dolore et qui corrupti quod minim pariatur Possimus', 'Local Kenyan Investigator', 'Dolorem mollitia id quis provident error voluptatem voluptate', 'Dolore laborum delectus dolor corrupti rerum aut magnam impedit quia cillum et cupiditate aliquid sapiente', '+1 (666) 325-1183', '+1 (314) 248-5575', '+1 (134) 102-6236', 'tuhokelyh@mailinator.com', '2025-01-13 12:13:08', '2025-02-13 08:07:46'),
(41, 160, 'Enim sint voluptatem eos nobis reiciendis dolore et qui corrupti quod minim pariatur Possimus', 'Local Kenyan Investigator', 'Dolorem mollitia id quis provident error voluptatem voluptate', 'Dolore laborum delectus dolor corrupti rerum aut magnam impedit quia cillum et cupiditate aliquid sapiente', '+1 (666) 325-1183', '+1 (314) 248-5575', '+1 (134) 102-6236', 'tuhokelyh@mailinator.com', '2025-01-13 12:13:08', '2025-02-06 10:49:03'),
(42, 161, 'Modi officia necessitatibus qui qui fuga Dolore eum eligendi', 'Others', 'Ullamco aspernatur dolor incididunt in dolore molestiae necessitatibus hic assumenda expedita explicabo Aliquid voluptas sunt explicabo Odit id maxime aliquid', 'Quidem rerum odio ipsum quia obcaecati saepe voluptatum in eos aut debitis quidem iusto', '+1 (777) 763-4616', '+1 (521) 133-9772', '+1 (367) 935-5004', 'megekof@mailinator.com', '2025-01-14 15:08:21', '2025-01-23 08:38:50'),
(43, 162, 'Modi officia necessitatibus qui qui fuga Dolore eum eligendi', 'Others', 'Ullamco aspernatur dolor incididunt in dolore molestiae necessitatibus hic assumenda expedita explicabo Aliquid voluptas sunt explicabo Odit id maxime aliquid', 'Quidem rerum odio ipsum quia obcaecati saepe voluptatum in eos aut debitis quidem iusto', '+1 (777) 763-4616', '+1 (521) 133-9772', '+1 (367) 935-5004', 'megekof@mailinator.com', '2025-01-14 15:08:21', '2025-01-24 15:58:39'),
(44, 187, 'Voluptas architecto sapiente corporis rem nihil officiis itaque', 'Local Kenyan Investigator', 'Quo ipsam labore quis ad necessitatibus rerum explicabo Rerum fuga Nam ratione et duis nihil error totam doloribus', 'Provident irure explicabo Duis nihil enim totam ut', '0700432876', '0700321876', '0700343232', 'hegito@mailinator.com', '2024-06-11 06:27:44', '2025-02-13 08:26:26'),
(45, 188, 'Voluptas architecto sapiente corporis rem nihil officiis itaque', 'Local Kenyan Investigator', 'Quo ipsam labore quis ad necessitatibus rerum explicabo Rerum fuga Nam ratione et duis nihil error totam doloribus', 'Provident irure explicabo Duis nihil enim totam ut', '0700432876', '0700321876', '0700343232', 'hegito@mailinator.com', '2024-06-11 06:27:44', '2025-02-13 10:51:46'),
(46, 191, 'BioPharma Innovations Ltd.', 'Industry', '', 'BioPharma Innovations Ltd. 123 Main Street San Francisco, CA 94105 United States', '+1 415-555-6789', '', '+1 415-555-6778', 'info@biopharmainnovations.com', '2024-06-20 10:55:03', '2025-02-18 09:59:26'),
(47, 195, 'Aut voluptatum est ab voluptas tempore aut et voluptas dolore architecto ad suscipit saepe omnis quaerat', 'Local Kenyan Investigator', 'Quas ut consectetur ratione est tempor quo odio iure esse occaecat eius suscipit explicabo Quasi sit', 'Excepteur distinctio Enim vitae reiciendis aut aliquid incididunt repudiandae nesciunt beatae adipisci', '+1 (731) 766-1698', '+1 (493) 753-7389', '+1 (179) 791-1499', 'covoginoby@mailinator.com', '2025-01-07 09:33:40', '2025-02-17 10:51:23'),
(48, 196, 'Incidunt quam eligendi commodi alias possimus fugiat ullam animi suscipit veritatis perspiciatis ad enim ut minima', 'Research Institution', 'Officia sunt ut ea et ex in consequatur quia et', 'Illum ipsa ea nisi qui atque repellendus', '+11938667688', '+19741752234', '+18795671669', 'zifiqi@mailinator.com', '2025-02-19 08:52:20', '2025-02-19 09:13:23'),
(49, 197, 'Incidunt quam eligendi commodi alias possimus fugiat ullam animi suscipit veritatis perspiciatis ad enim ut minima', 'Research Institution', 'Officia sunt ut ea et ex in consequatur quia et', 'Illum ipsa ea nisi qui atque repellendus', '+11938667688', '+19741752234', '+18795671669', 'zifiqi@mailinator.com', '2025-02-19 08:52:20', '2025-02-19 09:13:23'),
(50, 198, '', '', '', '', '', '', '', '', '2025-02-24 09:48:33', '2025-02-24 09:49:55'),
(51, 224, '', '', '', '', '', '', '', '', '2025-03-24 12:33:26', '2025-03-24 12:33:26'),
(52, 227, '', '', '', '', '', '', '', '', '2025-03-26 14:44:55', '2025-03-27 10:59:05'),
(53, 228, '', '', '', '', '', '', '', '', '2025-03-27 12:08:05', '2025-03-27 12:08:29'),
(54, 229, '', '', '', '', '', '', '', '', '2025-03-27 12:13:18', '2025-03-27 12:13:40'),
(55, 222, '', '', '', '', '', '', '', '', '2025-03-27 12:50:52', '2025-03-27 12:50:52'),
(56, 231, 'sample sample', 'Industry', '', 'sample', '07005439282', '', '07005439292', 'sample@gmail.com', '2025-03-27 12:54:56', '2025-07-11 17:33:23'),
(57, 232, '', '', '', '', '', '', '', '', '2025-03-28 08:14:17', '2025-03-28 08:44:49'),
(58, 234, '', '', '', '', '', '', '', '', '2025-03-28 09:06:35', '2025-03-28 09:07:05'),
(59, 235, '', '', '', '', '', '', '', '', '2025-03-28 09:19:10', '2025-03-28 09:31:31'),
(60, 236, '', '', '', '', '', '', '', '', '2025-03-28 09:36:37', '2025-03-28 10:17:38'),
(61, 237, '', '', '', '', '', '', '', '', '2025-03-28 10:22:49', '2025-03-28 10:22:49'),
(62, 238, '', '', '', '', '', '', '', '', '2025-03-28 10:27:15', '2025-03-28 10:27:15'),
(63, 239, '', '', '', '', '', '', '', '', '2025-03-28 11:01:28', '2025-03-28 11:38:25'),
(64, 240, '', '', '', '', '', '', '', '', '2025-03-28 12:15:38', '2025-03-28 12:18:00'),
(65, 241, '', '', '', '', '', '', '', '', '2025-03-28 12:30:00', '2025-03-28 12:30:00'),
(66, 242, '', '', '', '', '', '', '', '', '2025-03-28 17:33:43', '2025-03-28 17:35:35'),
(67, 243, '', '', '', '', '', '', '', '', '2025-04-01 12:05:35', '2025-04-01 12:07:49'),
(68, 244, '', '', '', '', '', '', '', '', '2025-04-01 12:16:18', '2025-04-01 12:18:19'),
(69, 245, '', '', '', '', '', '', '', '', '2025-04-04 14:19:45', '2025-04-04 14:22:23'),
(70, 190, '', '', '', '', '', '', '', '', '2025-04-10 22:41:28', '2025-04-10 22:41:28'),
(71, 223, '', '', '', '', '', '', '', '', '2025-05-30 09:03:54', '2025-05-30 09:03:54'),
(72, 246, '', '', '', '', '', '', '', '', '2026-02-03 16:30:59', '2026-02-03 16:51:54'),
(73, 247, 'Libero et adipisicing officia consequatur id et sunt et necessitatibus dignissimos eius nihil eaque ullamco molestiae culpa', 'Local Kenyan Investigator', 'Ut est consequat Aliquip magna consequatur Laboris deserunt ea excepturi nobis dolorem', 'Nostrud numquam architecto et corporis accusamus fugiat eos dignissimos dolore laborum Amet quaerat exercitationem voluptas', '+1 (676) 826-2667', '+1 (602) 779-9532', '+1 (516) 371-7186', 'koxaveh@mailinator.com', '2026-02-23 06:51:20', '2026-02-23 06:54:20'),
(74, 248, 'In sit blanditiis esse dolores eius sunt', 'Others', 'Sint asperiores doloribus velit voluptatem dolor sed dolor ullamco ea consectetur quia sint ipsam nisi veniam', 'Alias eum veniam incididunt vitae cumque laborum esse sapiente possimus deleniti dolorum sed', '+1 (173) 528-1736', '+1 (825) 352-3303', '+1 (993) 549-7521', 'moxuji@mailinator.com', '2026-02-23 07:01:57', '2026-02-25 12:07:29'),
(75, 249, 'Sint esse rerum illo tempor ex velit animi molestiae magnam', 'Local Kenyan Investigator', 'Quae ut deleniti quidem quidem totam quod quia', 'Tempor qui obcaecati nostrum tenetur dolor perspiciatis sint magni', '+1 (957) 328-2251', '+1 (686) 994-8364', '+1 (761) 946-8678', 'wohafahibi@mailinator.com', '2026-02-23 07:08:37', '2026-02-23 07:08:37'),
(76, 249, 'Ea nesciunt pariatur Nemo excepturi aut est cillum numquam', 'Others', 'Doloribus enim et at ipsum distinctio Perferendis reprehenderit magni eu explicabo Quia reiciendis perferendis omnis architecto nulla sit tempore reiciendis', 'Commodo non quis est adipisci quaerat', '+1 (117) 101-1532', '+1 (384) 353-8905', '+1 (161) 424-2784', 'hejybemo@mailinator.com', '2026-02-23 07:08:37', '2026-02-23 07:08:37'),
(77, 255, 'mmust', 'Research Institution', 'Head of reseach mmust', '', '', '', '0712345678', 'reasearch@gmail.com', '2026-06-09 13:30:08', '2026-06-09 14:22:48');

-- --------------------------------------------------------

--
-- Table structure for table `sponsor_organizations`
--

CREATE TABLE `sponsor_organizations` (
  `id` int NOT NULL,
  `application_id` int DEFAULT NULL,
  `organisations_transferred_name` varchar(255) DEFAULT NULL,
  `organisations_transferred_contact_person` varchar(255) DEFAULT NULL,
  `organisations_transferred_address` varchar(255) DEFAULT NULL,
  `organisations_transferred_telephone_number` varchar(255) DEFAULT NULL,
  `organisations_transferred_all_tasks` varchar(255) DEFAULT NULL,
  `organisations_transferred_monitoring` varchar(255) DEFAULT NULL,
  `organisations_transferred_regulatory` varchar(255) DEFAULT NULL,
  `organisations_transferred_investigator_recruitement` varchar(255) DEFAULT NULL,
  `organisations_transferred_IVRS` varchar(255) DEFAULT NULL,
  `organisations_transferred_data_management` varchar(255) DEFAULT NULL,
  `organisations_transferred_edata_capture` varchar(255) DEFAULT NULL,
  `organisations_transferred_SUSAR_reporting` varchar(255) DEFAULT NULL,
  `organisations_transferred_quality_assurance_auditing` varchar(255) DEFAULT NULL,
  `organisations_transferred_statistical_analysis` varchar(255) DEFAULT NULL,
  `organisations_transferred_medical_writing` varchar(255) DEFAULT NULL,
  `organisations_transferred_other_duties_subcontracted` varchar(255) DEFAULT NULL,
  `organisations_transferred_specify` tinytext,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `study_monitors`
--

CREATE TABLE `study_monitors` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `application_id` int DEFAULT NULL,
  `amendment_id` int DEFAULT NULL,
  `owner_id` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `study_routes`
--

CREATE TABLE `study_routes` (
  `id` int NOT NULL,
  `application_id` int DEFAULT NULL,
  `study_route` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `suspected_drugs`
--

CREATE TABLE `suspected_drugs` (
  `id` int NOT NULL,
  `sae_id` int DEFAULT NULL,
  `generic_name` varchar(100) DEFAULT NULL,
  `dose` varchar(100) DEFAULT NULL,
  `route_id` int DEFAULT NULL,
  `indication` varchar(255) DEFAULT NULL,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `therapy_duration` varchar(255) DEFAULT NULL,
  `reaction_abate` varchar(100) DEFAULT NULL,
  `reaction_reappear` varchar(255) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `terminations`
--

CREATE TABLE `terminations` (
  `id` int NOT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `application_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `content` longtext,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `submitted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `trial_statuses`
--

CREATE TABLE `trial_statuses` (
  `id` int NOT NULL,
  `value` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `trial_statuses`
--

INSERT INTO `trial_statuses` (`id`, `value`, `name`, `created`, `modified`) VALUES
(1, 'Recruiting', 'Recruiting', '2012-10-19 16:07:50', '2020-04-16 10:03:22'),
(2, 'Not yet recruiting', 'Not yet recruiting', '2012-10-19 16:08:11', '2020-04-16 10:06:10'),
(3, 'Suspended', 'Suspended', '2012-10-19 16:08:38', '2020-04-16 10:03:55'),
(4, 'Stopped', 'Stopped', '2012-10-19 16:08:51', '2020-04-16 10:04:18'),
(5, 'Completed', 'Completed', '2012-10-19 16:09:04', '2020-04-16 10:04:33'),
(6, 'In follow-up', 'In follow-up', '2013-10-07 18:20:30', '2013-10-07 18:20:30'),
(7, 'Analysing', 'Analysing', '2013-10-07 18:21:02', '2013-10-07 18:21:02'),
(8, 'Writing-up', 'Writing-up', '2013-10-07 18:21:37', '2013-10-07 18:21:37'),
(9, 'Application withdrawn ', 'Application withdrawn ', '2020-04-16 10:05:24', '2020-04-16 10:05:24'),
(10, 'Paused or preparation for extension of study', 'Paused or preparation for extension of study', '2025-01-06 00:00:00', '2025-01-06 00:00:00'),
(11, 'Revoked', 'Revoked', '2025-08-08 09:47:11', '2025-08-08 09:47:11'),
(12, 'Ongoing Closeout Phase', 'Ongoing Closeout Phase', '2026-02-16 18:27:52', '2026-02-16 18:27:52');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` char(40) NOT NULL,
  `confirm_password` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `national_id_number` varchar(255) DEFAULT NULL,
  `sponsor` int DEFAULT NULL,
  `sponsor_email` varchar(50) DEFAULT NULL,
  `qualification` varchar(100) DEFAULT NULL,
  `phone_no` char(40) DEFAULT NULL,
  `name_of_institution` varchar(255) DEFAULT NULL,
  `institution_physical` varchar(255) DEFAULT NULL,
  `institution_address` varchar(255) DEFAULT NULL,
  `institution_contact` varchar(255) DEFAULT NULL,
  `county_id` int DEFAULT NULL,
  `country_id` int DEFAULT NULL,
  `group_id` int NOT NULL,
  `activation_key` varchar(200) DEFAULT NULL,
  `forgot_password` tinyint DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '0',
  `deactivated` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `auditor_protocols` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `application_id` int DEFAULT NULL,
  `owner_id` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `application_id` (`application_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acos`
--
ALTER TABLE `acos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `active_inspectors`
--
ALTER TABLE `active_inspectors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `amendments`
--
ALTER TABLE `amendments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `amendment_approvals`
--
ALTER TABLE `amendment_approvals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `amendment_letters`
--
ALTER TABLE `amendment_letters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `amends`
--
ALTER TABLE `amends`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `annual_letters`
--
ALTER TABLE `annual_letters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `application_stages`
--
ALTER TABLE `application_stages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aros`
--
ALTER TABLE `aros`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aros_acos`
--
ALTER TABLE `aros_acos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ARO_ACO_KEY` (`aro_id`,`aco_id`);

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `audit_trails`
--
ALTER TABLE `audit_trails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `budgets`
--
ALTER TABLE `budgets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cioms`
--
ALTER TABLE `cioms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `concomittant_drugs`
--
ALTER TABLE `concomittant_drugs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `counties`
--
ALTER TABLE `counties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deletion_settings`
--
ALTER TABLE `deletion_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deviations`
--
ALTER TABLE `deviations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ercs`
--
ALTER TABLE `ercs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ethical_committees`
--
ALTER TABLE `ethical_committees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `information_placebos`
--
ALTER TABLE `information_placebos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `investigators`
--
ALTER TABLE `investigators`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `investigator_contacts`
--
ALTER TABLE `investigator_contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manufacturers`
--
ALTER TABLE `manufacturers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meeting_dates`
--
ALTER TABLE `meeting_dates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `multi_centers`
--
ALTER TABLE `multi_centers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `outsources`
--
ALTER TABLE `outsources`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `outsource_requests`
--
ALTER TABLE `outsource_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `participant_flows`
--
ALTER TABLE `participant_flows`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pharmacists`
--
ALTER TABLE `pharmacists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `placebos`
--
ALTER TABLE `placebos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pockets`
--
ALTER TABLE `pockets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `previous_dates`
--
ALTER TABLE `previous_dates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `principal_investigators`
--
ALTER TABLE `principal_investigators`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `protocol_outsources`
--
ALTER TABLE `protocol_outsources`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `queries`
--
ALTER TABLE `queries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reassignments`
--
ALTER TABLE `reassignments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviewers`
--
ALTER TABLE `reviewers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `review_answers`
--
ALTER TABLE `review_answers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `review_questions`
--
ALTER TABLE `review_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `saes`
--
ALTER TABLE `saes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sae_dates`
--
ALTER TABLE `sae_dates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `safety_reports`
--
ALTER TABLE `safety_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_answers`
--
ALTER TABLE `site_answers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_details`
--
ALTER TABLE `site_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_inspections`
--
ALTER TABLE `site_inspections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_questions`
--
ALTER TABLE `site_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sponsors`
--
ALTER TABLE `sponsors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sponsor_organizations`
--
ALTER TABLE `sponsor_organizations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `study_monitors`
--
ALTER TABLE `study_monitors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `study_routes`
--
ALTER TABLE `study_routes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suspected_drugs`
--
ALTER TABLE `suspected_drugs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `terminations`
--
ALTER TABLE `terminations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trial_statuses`
--
ALTER TABLE `trial_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `acos`
--
ALTER TABLE `acos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `active_inspectors`
--
ALTER TABLE `active_inspectors`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `amendments`
--
ALTER TABLE `amendments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `amendment_approvals`
--
ALTER TABLE `amendment_approvals`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `amendment_letters`
--
ALTER TABLE `amendment_letters`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `amends`
--
ALTER TABLE `amends`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `annual_letters`
--
ALTER TABLE `annual_letters`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `application_stages`
--
ALTER TABLE `application_stages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aros`
--
ALTER TABLE `aros`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aros_acos`
--
ALTER TABLE `aros_acos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_trails`
--
ALTER TABLE `audit_trails`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `budgets`
--
ALTER TABLE `budgets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cioms`
--
ALTER TABLE `cioms`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `concomittant_drugs`
--
ALTER TABLE `concomittant_drugs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `counties`
--
ALTER TABLE `counties`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=250;

--
-- AUTO_INCREMENT for table `deletion_settings`
--
ALTER TABLE `deletion_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deviations`
--
ALTER TABLE `deviations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ercs`
--
ALTER TABLE `ercs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `ethical_committees`
--
ALTER TABLE `ethical_committees`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `facilities`
--
ALTER TABLE `facilities`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `information_placebos`
--
ALTER TABLE `information_placebos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `investigators`
--
ALTER TABLE `investigators`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `investigator_contacts`
--
ALTER TABLE `investigator_contacts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `manufacturers`
--
ALTER TABLE `manufacturers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `meeting_dates`
--
ALTER TABLE `meeting_dates`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `multi_centers`
--
ALTER TABLE `multi_centers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `outsources`
--
ALTER TABLE `outsources`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `outsource_requests`
--
ALTER TABLE `outsource_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `participant_flows`
--
ALTER TABLE `participant_flows`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pharmacists`
--
ALTER TABLE `pharmacists`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `placebos`
--
ALTER TABLE `placebos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pockets`
--
ALTER TABLE `pockets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `previous_dates`
--
ALTER TABLE `previous_dates`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `principal_investigators`
--
ALTER TABLE `principal_investigators`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `protocol_outsources`
--
ALTER TABLE `protocol_outsources`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `queries`
--
ALTER TABLE `queries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reassignments`
--
ALTER TABLE `reassignments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviewers`
--
ALTER TABLE `reviewers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `review_answers`
--
ALTER TABLE `review_answers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `review_questions`
--
ALTER TABLE `review_questions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `routes`
--
ALTER TABLE `routes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `saes`
--
ALTER TABLE `saes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sae_dates`
--
ALTER TABLE `sae_dates`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `safety_reports`
--
ALTER TABLE `safety_reports`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `site_answers`
--
ALTER TABLE `site_answers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `site_details`
--
ALTER TABLE `site_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `site_inspections`
--
ALTER TABLE `site_inspections`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `site_questions`
--
ALTER TABLE `site_questions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=190;

--
-- AUTO_INCREMENT for table `sponsors`
--
ALTER TABLE `sponsors`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `sponsor_organizations`
--
ALTER TABLE `sponsor_organizations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `study_monitors`
--
ALTER TABLE `study_monitors`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `study_routes`
--
ALTER TABLE `study_routes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suspected_drugs`
--
ALTER TABLE `suspected_drugs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `terminations`
--
ALTER TABLE `terminations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trial_statuses`
--
ALTER TABLE `trial_statuses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;

--  declaration for non-AI generated protocols
    ALTER TABLE applications ADD COLUMN protocol_not_ai_generated TINYINT(1) DEFAULT 0 AFTER declaration_date2;
    COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
