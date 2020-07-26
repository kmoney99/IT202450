<html><head><link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
<!-- Include jQuery 3.5.1-->
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
</head><body data-gr-c-s-loaded="true"><nav class="navbar navbar-expand-lg navbar-dark bg-warning">
    <ul class="navbar-nav mr-auto" style="margin: 0 auto;">
                    <li class="nav-item">
                <a class="nav-link" style="
    color: black;
    font-weight: bold;
	margin: 10px;
	" href="/Project/includes/../home.php">Home</a>
            </li>
			<li class="nav-item">
                <a class="nav-link" style="
    color: black;
    font-weight: bold;
	margin: 10px;
	" href="/Project/includes/../search.php">Search</a>
            </li>
                <li class="nav-item">
                    <a class="nav-link" style="
    color: black;
    font-weight: bold;
	margin: 10px;
	" href="/Project/includes/../create_questionnaire.php">Create Custom Survey</a>
                </li>
				
            <li class="nav-item">
                <a class="nav-link" style="
    color: black;
    font-weight: bold;
	margin: 10px;
	" href="/Project/includes/../surveys.php">Surveys</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" style="
    color: black;
    font-weight: bold;
	margin: 10px;
	" href="/Project/includes/../TakenSurveys.php">Surveys you've Taken</a>
            </li>
                            <li class="nav-item">
                <a class="nav-link" style="
    color: black;
    font-weight: bold;
	margin: 10px;
	" href="/Project/includes/../logout.php">Logout</a>
            </li>
            </ul>
</nav>
<div id="messages">
        </div><div class="container-fluid">
    <form method="POST">
        <div class="form-group">
            <label for="questionnaire_name">Questionnaire Name</label>
            <input class="form-control" type="text" id="questionnaire_name" name="questionnaire_name" required="">
        </div>
        <div class="form-group">
            <label for="questionnaire_desc">Questionnaire Description</label>
            <textarea class="form-control" type="text" id="questionnaire_desc" name="questionnaire_desc"></textarea>
        </div>
        
        <div class="form-group">
            
            
        </div>
        
        <div class="list-group">
            <div class="list-group-item">
                <div class="form-group">
                    <label for="question_0">Question</label>
                    <input class="form-control" type="text" id="question_0" name="question_0" required="">
                </div>
                <button class="btn btn-danger" onclick="event.preventDefault(); deleteMe(this);">X</button>
                <div class="list-group">
                    <div class="list-group-item">
                        <div class="form-group">
                            <label for="question_0_answer_0">Answer</label>
                            <input class="form-control" type="text" id="question_0_answer_0" name="question_0_answer_0" required="">
                        </div>
                        
                        
                    </div>
                </div>
                <button class="btn btn-secondary" onclick="event.preventDefault(); cloneThis(this);">Add Answer</button>
            </div>
        </div>
        <button class="btn btn-secondary" onclick="event.preventDefault(); cloneThis(this);">Add Question</button>

        <div class="form-group">
            <input type="submit" name="submit" class="btn btn-primary" value="Create Questionnaire">
        </div>
    </form>
        <script>
        function update_names_and_ids($ele){
            let $lis = $ele.children(".list-group-item");
            //loop over all list-group-items of list-group
            $lis.each(function(index, item){
                let $fg = $(item).find(".form-group");
                let liIndex = index;
                //loop over all form-groups inside list-group-item
                $fg.each(function(index, item){
                    let $label = $(item).find("label");
                    if(typeof($label) !== 'undefined' && $label != null){
                        let forAttr = $label.attr("for");
                        let pieces = forAttr.split('_');
                        //Note this is different since it's a plain array not a jquery object
                        pieces.forEach(function(item, index){
                            if(!isNaN(item)){
                                pieces[index] = liIndex;
                            }
                        });
                        let updatedRef = pieces.join("_");
                        $label.attr("for", updatedRef);
                        let $input = $(item).find(":input");
                        if(typeof($input) !== 'undefined' && $input != null){
                            $input.attr("id", updatedRef);
                            $input.attr("name", updatedRef);
                        }
                    }
                });
                //See if we have any children list-groups (this would be our answers)
                let $child_lg = $(item).find(".list-group");//probably doesn't need an each loop but it's fine
                $child_lg.each(function(index, item){
                    let $childlis = $(item).find(".list-group-item");
                    $childlis.each(function (index, item) {
                        let $fg = $(item).find(".form-group");
                        let childLiIndex = index;
                        //loop over all form-groups inside list-group-item
                        $fg.each(function(index, item){
                            let $label = $(item).find("label");
                            if(typeof($label) !== 'undefined' && $label != null){
                                let forAttr = $label.attr("for");
                                let pieces = forAttr.split('_');
                                //Note this is different since it's a plain array not a jquery object
                                let lastIndex = -1;
                                pieces.forEach(function(item, index){
                                    if(!isNaN(item)){
                                        //question_#_answer_#
                                        if(lastIndex == -1) {
                                            //question_#
                                            pieces[index] = liIndex;//replace the first # with the parent outer loop index
                                            lastIndex = index;
                                        }
                                        else{
                                            //question_#_answer_#
                                            pieces[index] = childLiIndex;//replace the second # with the child loop index
                                        }
                                    }
                                });
                                let updatedRef = pieces.join("_");
                                $label.attr("for", updatedRef);
                                let $input = $(item).find(":input");
                                if(typeof($input) !== 'undefined' && $input != null){
                                    $input.attr("id", updatedRef);
                                    $input.attr("name", updatedRef);
                                }
                            }
                        });
                    });
                });
            });
        }
        function cloneThis(ele){
            let $lg = $(ele).siblings(".list-group");
            let $li = $lg.find(".list-group-item:first");
            let $clone = $li.clone();
            $lg.append($clone);
            update_names_and_ids($(".list-group:first"));
        }
        function deleteMe(ele){
            let $li = $(ele).closest(".list-group-item");
            let $lg = $li.closest(".list-group");
            let $children = $lg.children(".list-group-item");
            if($children.length > 1){
                $li.remove();
                update_names_and_ids($(".list-group:first"));
            }
        }
    </script>
</div>
</body></html>