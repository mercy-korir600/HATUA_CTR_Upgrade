<?php
$this->assign('Applications', 'active');
$checklistUrl = 'http://www.pharmacyboardkenya.org/index.php?id=15';
?>

<style>
    .app-create-shell {
        margin-top: 10px;
    }

    .app-create-stack {
        max-width: 1120px;
        margin: 0 auto;
    }

    .app-create-card {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 10px;
        box-shadow: 0 14px 34px rgba(0, 0, 0, 0.08);
    }

    .app-create-card + .app-create-card {
        margin-top: 18px;
    }

    .app-create-intro-card {
        position: relative;
        padding: 20px 24px 20px 26px;
    }

    .app-create-intro-card:before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        width: 4px;
        background: #5cb85c;
        border-radius: 8px 0 0 8px;
    }

    .app-create-intro-card p {
        margin: 0 0 10px 0;
        line-height: 1.6;
        font-size: 14px;
    }

    .app-create-intro-card p:last-child {
        margin-bottom: 0;
    }

    .app-create-intro-card a {
        color: inherit;
        text-decoration: underline;
    }

    .app-create-form-head {
        border-bottom: 1px solid #eee;
        padding: 16px 20px 14px 20px;
        background: #fafafa;
        border-radius: 10px 10px 0 0;
    }

    .app-create-form-head-meta {
        margin-top: 8px;
    }

    .app-create-form-head-meta .label {
        margin-right: 6px;
    }

    .app-create-form-head h4 {
        margin: 0;
    }

    .app-create-form-head .muted {
        margin: 7px 0 0 0;
        font-size: 13px;
    }

    .app-create-form-body {
        padding: 20px;
    }

    .app-create-form .input {
        margin-bottom: 14px;
        padding: 10px 12px;
        border: 1px solid #e6e6e6;
        border-radius: 8px;
        background: #fcfcfc;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
    }

    .app-create-form .input:focus-within {
        border-color: #5cb85c;
        box-shadow: 0 0 0 2px rgba(92, 184, 92, 0.16);
        background: #fff;
    }

    .app-create-form label {
        font-weight: 700;
        font-size: 12px;
        line-height: 1.4;
        letter-spacing: 0.01em;
        color: #555;
        margin-bottom: 6px;
    }

    .app-create-form input[type="text"],
    .app-create-form input[type="email"],
    .app-create-form input[type="number"] {
        width: 100%;
        box-sizing: border-box;
        min-height: 36px;
        border-radius: 5px;
        border-color: #ccc;
        margin-bottom: 0;
        box-shadow: none;
        background: #fff;
    }

    .app-create-form input[type="text"]:focus,
    .app-create-form input[type="email"]:focus,
    .app-create-form input[type="number"]:focus {
        border-color: #5cb85c;
        box-shadow: 0 0 0 2px rgba(92, 184, 92, 0.12);
    }

    .app-create-section {
        margin-top: 10px;
        border-top: 1px solid #eee;
        padding-top: 16px;
    }

    .app-create-section h5 {
        margin: 0 0 14px 0;
        font-weight: 700;
        letter-spacing: 0.04em;
        color: #4a4a4a;
    }

    .app-create-actions {
        margin-top: 14px;
        border-top: 1px solid #eee;
        padding-top: 14px;
        text-align: right;
    }

    @media (max-width: 979px) {
        .app-create-shell .row-fluid [class*="span"] {
            width: 100%;
            margin-left: 0;
        }

        .app-create-intro-card,
        .app-create-form-head,
        .app-create-form-body {
            padding-left: 14px;
            padding-right: 14px;
        }

        .app-create-form .input-xlarge,
        .app-create-form .input-xxlarge {
            width: 100%;
            box-sizing: border-box;
        }
    }
</style>

<div class="row-fluid app-create-shell">
    <div class="span12">
        <div class="app-create-stack">
            <?php echo $this->fetch('header'); ?>
            <?php echo $this->Session->flash(); ?>

            <div class="app-create-card app-create-intro-card">
                <p>Thank you for your interest in registering your application to conduct a clinical trial.</p>
                <p>Your trial will go through various stages until its approved.</p>
                <p>Once your create an application, you are allowed to update it as much as you want before submitting. Please ensure you meet all the requirements of the Pharmacy and Poisons Board checklist available here (<a href="<?php echo $checklistUrl; ?>" target="_blank" rel="noopener noreferrer"><?php echo h($checklistUrl); ?></a>) before you submit the application.</p>
                <p>Once you submit an application, you will not be able to make further changes to it.</p>
            </div>

            <div class="app-create-card app-create-form-card">
                <div class="app-create-form-head">
                    <h4 class="text-success">New Application</h4>
                    <p class="muted">Provide the details below to create your application.</p>
                    <div class="app-create-form-head-meta">
                        <span class="label label-success">Draft</span>
                        <span class="label">Can be edited before submit</span>
                    </div>
                </div>

                <div class="app-create-form-body">
                    <?php
                    echo $this->Form->create('Application', array('class' => 'app-create-form'));
                    echo $this->Form->input('deactivated', array('type' => 'hidden', 'value' => 0));
                    ?>

                    <div class="row-fluid">
                        <div class="span6">
                            <?php
                            echo $this->Form->input('email_address', array(
                                'type' => 'email',
                                'value' => $this->Session->read('Auth.User.email'),
                                'label' => array('class' => 'control-label required', 'text' => 'Email Address <span class="sterix">*</span>'),
                                'class' => 'input-xxlarge',
                                'placeholder' => 'you@example.com',
                                'readonly' => 'readonly'
                            ));
                            ?>
                        </div>
                        <div class="span2">
                            <?php
                            echo $this->Form->input('total_sites', array(
                                'type' => 'number',
                                'min' => 1,
                                'label' => array('class' => 'control-label required', 'text' => 'Total Sites <span class="sterix">*</span>')
                            ));
                            ?>
                        </div>
                        <div class="span4">
                            <?php
                            echo $this->Form->input('short_title', array(
                                'label' => array('class' => 'control-label required', 'text' => 'Short Title <span class="sterix">*</span>'),
                                'maxlength' => 30,
                                'placeholder' => ' ',
                                'class' => 'input-xxlarge'
                            ));
                            ?>
                        </div>
                    </div>

                    <div class="app-create-section">
                        <h5>PRINCIPAL INVESTIGATOR</h5>

                        <?php
                        echo $this->Html->tag('hr', '', array('id' => 'InvestigatorContactHr0'));
                        ?>

                        <div class="row-fluid">
                            <div class="span4">
                                <?php
                                echo $this->Form->input('InvestigatorContact.0.id');
                                echo $this->Form->input('InvestigatorContact.0.given_name', array(
                                    'label' => array('class' => 'control-label required', 'text' => 'Given Name <span class="sterix">*</span>'),
                                    'placeholder' => ' ',
                                    'class' => 'input-xlarge'
                                ));
                                ?>
                            </div>
                            <div class="span4">
                                <?php
                                echo $this->Form->input('InvestigatorContact.0.middle_name', array(
                                    'label' => array('class' => 'control-label', 'text' => 'Middle Name, if applicable'),
                                    'placeholder' => ' ',
                                    'class' => 'input-xlarge'
                                ));
                                ?>
                            </div>
                            <div class="span4">
                                <?php
                                echo $this->Form->input('InvestigatorContact.0.family_name', array(
                                    'label' => array('class' => 'control-label required', 'text' => 'Family Name <span class="sterix">*</span>'),
                                    'placeholder' => ' ',
                                    'class' => 'input-xlarge'
                                ));
                                ?>
                            </div>
                        </div>

                        <div class="row-fluid">
                            <div class="span3">
                                <?php
                                echo $this->Form->input('InvestigatorContact.0.qualification', array(
                                    'label' => array('class' => 'control-label required', 'text' => 'Qualification <span class="sterix">*</span>'),
                                    'placeholder' => ' ',
                                    'class' => 'input-xlarge'
                                ));
                                ?>
                            </div>
                            <div class="span3">
                                <?php
                                echo $this->Form->input('InvestigatorContact.0.professional_address', array(
                                    'label' => array('class' => 'control-label required', 'text' => 'Professional Address <span class="sterix">*</span>'),
                                    'placeholder' => ' ',
                                    'class' => 'input-xlarge'
                                ));
                                ?>
                            </div>
                        </div>

                        <div class="row-fluid">
                            <div class="span3">
                                <?php
                                echo $this->Form->input('InvestigatorContact.0.telephone', array(
                                    'label' => array('class' => 'control-label required', 'text' => 'Telephone Number <span class="sterix">*</span>'),
                                    'placeholder' => ' ',
                                    'class' => 'input-xlarge'
                                ));
                                ?>
                            </div>
                            <div class="span3">
                                <?php
                                echo $this->Form->input('InvestigatorContact.0.email', array(
                                    'type' => 'email',
                                    'label' => array('class' => 'control-label required', 'text' => 'Email Address <span class="sterix">*</span>'),
                                    'placeholder' => ' ',
                                    'class' => 'input-xlarge'
                                ));
                                ?>
                            </div>
                        </div>

                        <?php
                        echo $this->Html->tag('hr', '', array('id' => 'InvestigatorContactHr0'));
                        ?>
                    </div>

                    <div class="app-create-actions">
                        <?php
                        echo $this->Form->end(array(
                            'label' => 'Create',
                            'value' => 'Create',
                            'class' => 'btn btn-success btn-large'
                        ));
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
