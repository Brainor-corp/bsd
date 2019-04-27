$(document).ready(function () {
    $('#review-files').change(function () {
       let fileName = this.files[0].name;
       $('#file-name-wrapper').text(fileName);
    });
});