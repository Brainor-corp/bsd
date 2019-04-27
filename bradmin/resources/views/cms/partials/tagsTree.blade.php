@if(isset($tags_tree))
    <div class="form-group form-element-text ">
        <label for="parent_id" class="control-label">Родитель</label>
        <div style="border: 1px solid #d2d6de;padding: 0 10px;max-height: 400px; overflow: scroll">
            <?php
            $level = 1;
            function findKey($array, $keySearch)
            {
                foreach ($array as $item) {
                    if (isset($item['id'])) {
                        if ($item['id'] == $keySearch) {
                            return true;
                        }
                        else {
                            if (is_array($item) && findKey($item, $keySearch)) {
                                return true;
                            }
                        }
                    }
                    else {
                        if (is_array($item) && findKey($item, $keySearch)) {
                            return true;
                        }
                    }
                }

                return false;
            }
            function tplMenu($node, $level,$cur_tag){
            if($level == 1): ?><?php endif; ?>
            <?php if(isset($node['children']) AND count($node['children'])>0): ?>
            <div style="clear: both"></div>
            <div class="radio" style="float: left;">
                <label>
                    <input type="radio" name="parent_id" id="parent_id" value="{{$node['id']}}" @if($cur_tag['parent_id'] == $node['id']) checked @endif @if($cur_tag['id'] == $node['id']) disabled @endif> {{$node['title']}} <a href="/admin/terms/{{$node['id']}}/edit" style="color: #a5cbe0;"><i class="fa fa-external-link" aria-hidden="true"></i></a>

                </label>
            </div>
            <span data-toggle="collapse" data-target="#post_parent_{{$node['id']}}" style="border-bottom: 1px #c63e9b dashed;color: #c63e9b;cursor: pointer;    margin-top: 10px;margin-bottom: 10px;margin-left: 10px;display: inline-block;">Развернуть</span>
            <div id="post_parent_{{$node['id']}}" class="collapse @if(findKey($node,$cur_tag['id'])) in @endif" style="padding-left: 10px;border-left: 1px solid #c1c1c1;">
                <?php
                $level++;
                foreach ($node['children'] as $key => $row) {
                    $tags_tree_title[$key]  = $row['title'];
                }
                array_multisort($tags_tree_title, SORT_NATURAL, SORT_ASC,$node['children']);
                showCat($node['children'], $level,$cur_tag);
                ?>
                <div style="clear: both"></div>
            </div>
            <?php else: ?>
            <div style="clear: both"></div>
            <div class="radio" style="float: left;">
                <label  @if($cur_tag['id'] == $node['id']) style="font-weight: 600" @endif>
                    <input type="radio" name="parent_id" id="parent_id" value="{{$node['id']}}" @if($cur_tag['parent_id'] == $node['id']) checked @endif @if($cur_tag['id'] == $node['id']) disabled @endif> {{$node['title']}} <a href="/admin/terms/{{$node['id']}}/edit" style="color: #a5cbe0;"><i class="fa fa-external-link" aria-hidden="true"></i></a>
                </label>
            </div>
            <?php endif; ?>

            <?php }
            /**
             * Рекурсивно считываем наш шаблон
             **/
            function showCat($data, $level,$cur_tag){
                $string = '';
                foreach($data as $item){
                    $string .= tplMenu($item,$level,$cur_tag);
                }
                return $string;
            }

            //Получаем HTML разметку
            $cat_menu = showCat($tags_tree,$level,$cur_tag);
            ?>
            <div style="clear: both"></div>
        </div>
    </div>
@endif