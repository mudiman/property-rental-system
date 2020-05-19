/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var url = window.location.toString();

// for sidebar menu entirely but not cover treeview
$('ul.sidebar-menu a').filter(function () {
    return url.indexOf(this.href) > -1;
}).parent().addClass('active');

// for treeview
$('ul.treeview-menu a').filter(function () {
    return url.indexOf(this.href) > -1;
}).closest('.treeview').addClass('active');


$(function () {
    $('.datetimepicker').datetimepicker({
        format: 'YYYY-MM-DD hh:mm:ss'
    });
    
    $('.datepicker').datetimepicker({
        format: 'YYYY-MM-DD'
    });
    
    $('.timepicker').datetimepicker({
        format: 'hh:mm'
    });
});