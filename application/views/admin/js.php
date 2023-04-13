<!-- Theme JS files -->
<script type="text/javascript" src="<?= base_url('assets/js/core/libraries/jasny_bootstrap.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/forms/wizards/stepy.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/forms/tags/tagsinput.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/forms/tags/tokenfield.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/forms/editable/editable.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/core/libraries/jquery_ui/interactions.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/core/libraries/jquery_ui/widgets.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/core/libraries/jquery_ui/effects.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/extensions/mousewheel.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/core/libraries/jquery_ui/globalize/globalize.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/core/libraries/jquery_ui/globalize/cultures/globalize.culture.de-DE.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/core/libraries/jquery_ui/globalize/cultures/globalize.culture.ja-JP.js') ?>"></script>

<script type="text/javascript" src="<?= base_url('assets/js/plugins/uploaders/dropify/dist/js/dropify.js') ?>"></script>

<script type="text/javascript" src="<?= base_url('assets/js/plugins/visualization/d3/d3.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/visualization/d3/d3_tooltip.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/forms/styling/switchery.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/forms/styling/uniform.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/forms/styling/switch.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/forms/selects/bootstrap_multiselect.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/ui/moment/moment.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/pickers/anytime.min.js"></script>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/pickers/daterangepicker.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/extensions/jquery.mask.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/tables/datatables/datatables.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/forms/styling/switchery.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/forms/styling/switch.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/pickers/pickadate/picker.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/pickers/pickadate/picker.date.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/pickers/pickadate/picker.time.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/pickers/pickadate/legacy.js') ?>"></script>

<script type="text/javascript" src="<?= base_url('assets/js/core/app.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/pages/extra_trees.js"></script>
<!-- <script type="text/javascript" src="<?= base_url('assets/js/pages/wizard_stepy.js') ?>"></script> -->

<script type="text/javascript" src="<?= base_url('assets/js/plugins/ui/ripple.min.js') ?>"></script>

<script type="text/javascript" src="<?= base_url('assets/js/pages/form_floating_labels.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/pages/form_checkboxes_radios.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/pages/dashboard.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/pages/form_select2.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/pages/form_editable.js') ?>"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/script.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/js-form.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/pagination.js"></script>
<script>
    // Select with search
      // $("#anytime-time").AnyTime_picker({
      //     format: "%H:%i:%s"
      // });

      // $(".time-picker").AnyTime_picker({
      //     format: "%H:%i:%s"
      // });      

      $('.select-search').select2({
        containerCssClass: 'bg-success-400'
      });

      $(`.select-search-warning`).select2({
      containerCssClass : 'bg-warning-400'
      })

      $(`.select-search-primary`).select2({
        containerCssClass : 'bg-primary-400'
      })

      $('.select').select2({
          minimumResultsForSearch: Infinity,
          containerCssClass: 'bg-success-400'
      });

      $('.select-indigo').select2({
          minimumResultsForSearch: Infinity,
          containerCssClass: 'bg-indigo-400',
          tags : true
      });

    $('.select-primary').select2({
          minimumResultsForSearch: Infinity,
          containerCssClass: 'bg-primary-400',
          tags : true
      });

      $(`.select-warning`).select2({
        minimumResultsForSearch: Infinity,
          containerCssClass: 'bg-warning-400'
      })

    $('.rupiah').mask('000.000.000', {reverse: true});

      $(document).find(".datepicker").datepicker({
          showOtherMonths: true,
          selectOtherMonths: true,
          showButtonPanel: true,
          dateFormat : 'dd-mm-yy'
      });
          // Default initialization
      $(".styled, .multiselect-container input").uniform({
          radioClass: 'choice'
      });
       // Primary
      $(".control-primary").uniform({
          radioClass: 'choice',
          wrapperClass: 'border-primary-600 text-primary-800'
      });

      // Danger
      $(".control-danger").uniform({
          radioClass: 'choice',
          wrapperClass: 'border-danger-600 text-danger-800'
      });

      // Success
      $(".control-success").uniform({
          radioClass: 'choice',
          wrapperClass: 'border-success-600 text-success-800'
      });

      // Warning
      $(".control-warning").uniform({
          radioClass: 'choice',
          wrapperClass: 'border-warning-600 text-warning-800'
      });

      // Info
      $(".control-info").uniform({
          radioClass: 'choice',
          wrapperClass: 'border-info-600 text-info-800'
      });

      // Custom color
      $(".control-custom").uniform({
          radioClass: 'choice',
          wrapperClass: 'border-indigo-600 text-indigo-800'
      });
      // Editable text field
      $('#text-field').editable();
      $('.tokenfield').tokenfield();
    // Colored switches
      var primary = document.querySelector('.switchery-primary');
      var switchery = new Switchery(primary, { color: '#2196F3' });

    let timestamp = (date) => {

    }

    let date_indonesian = (date_object, status) => {
      let months_abjad = ['January', 'February', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'November', 'Desember'];
      let date = (date_object.getDate() >= 10) ? date_object.getDate() : `0${date_object.getDate()}`;
      let month_abjad = months_abjad[date_object.getMonth()];
      let month_number = (date_object.getMonth() + 1 >= 10) ? date_object.getMonth() + 1 : `0${date_object.getMonth() + 1}`;
      let year = date_object.getFullYear();

      let format_1 = `${date}-${month_abjad}-${year}`;
      let format_2 = `${date}-${month_number}-${year}`;

      return status == true ? format_1 : format_2;
    }

</script>
