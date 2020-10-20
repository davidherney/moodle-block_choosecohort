// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Javascript to initialise the block.
 *
 * @package   block_choosecohort
 * @copyright 2020 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core/modal_factory', 'core/templates', 'core/notification'],
        function($, ModalFactory, Templates, Notification) {

    var wwwroot = M.cfg.wwwroot;
    var detailwindow = null;

    ModalFactory.create({
        body: ''
    })
    .then(function(modal) {
        detailwindow = modal;
        modal.getModal().addClass('block_choosecohort-modal');
    });

    /**
     * Initialise all for the block.
     *
     */
    var init = function() {

        $('.block_choosecohort').each(function(i, v) {
            var $_this = $(this);
            var $searchtext = $_this.find('[data-control="search-text"]');

            $errorbox = $_this.find('[data-control="errors-box"]');

            var filter = function() {
                var val = $searchtext.val();
                $_this.find('.choosecohort-content .one-cohort').hide();
                $_this.find(".one-cohort :contains('" + val + "')").parents('.one-cohort').show();

            };

            $_this.find('[data-control="search-button"]').on('click', function(){
                filter();
            });

            $searchtext.on('keypress', function(event) {
                if (event.keyCode) {
                    switch(event.keyCode) {
                        case 13: // Enter
                            event.preventDefault();
                            break;
                    }
                }
            });

            $searchtext.on('keyup', function(event) {

                var specialKeys = [13, 16, 17, 18, 27, 33, 34, 35, 36, 37, 38, 39, 45, 144];

                if ((event.keyCode && specialKeys.indexOf(event.keyCode) == -1) || event.keyCode === 13) {
                    filter();
                }
            });

            $_this.find('[type="checkbox"]').change(function() {
                $.get(wwwroot + '/blocks/choosecohort/choose.php?idnumber=' + $(this).val());
            });

            $_this.find('[data-control="view-detail"]').on('click', function() {
                var $me = $(this);
                var $parent = $me.parents('.one-cohort');

                detailwindow.setBody($parent.find('.one-description').html());
                detailwindow.show();
            });

        });

    };

    return {
        init: init
    };
});
