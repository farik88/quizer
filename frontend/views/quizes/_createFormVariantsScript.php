<?php 
use yii\helpers\Url; 
?>
<script> 
    
    $(document).ready(function(){
        // Add first variant row
        addRowVariants();
    });
    
    function addRow<?= $class ?>() {
        var data = $('#add-<?= $relID?> :input').serializeArray();
        data.push({name: '_action', value : 'add'});
        $.ajax({
            type: 'POST',
            url: '<?php echo Url::to(['add-'.$relID]); ?>',
            data: data,
            success: function (data) {
                $('#add-<?= $relID?>').html(data);
            }
        });
    }
    function delRow<?= $class ?>(id) {
    
        <?php if(isset($model_id) && !empty($model_id) && is_int($model_id)){ ?>
                if (confirm("Are you sure?")) {
                    $.ajax({
                        type: 'POST', 
                        url: '<?php echo Url::to(['quizes/delete-variant']); ?>',
                        data : {
                            quize_id : <?= $model_id; ?>,
                            text : $('#variants-' + id + '-text').val()
                        },
                        success: function (data) {
                        }
                    });
                    $('#add-<?= $relID?> tr[data-key=' + id + ']').remove();
                }
        <?php } else { ?>
            $('#add-<?= $relID?> tr[data-key=' + id + ']').remove();
        <?php } ?>
        
    }
</script>