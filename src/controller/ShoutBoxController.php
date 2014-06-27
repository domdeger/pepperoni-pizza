<?php

class ShoutboxController
{

    public  function  Login() {
        echo '
                    <!-- Default home page -->
            <form method="POST" action="/Authentication/Login">
               <label for="username">Username</label>
                <input type="text" name="username" />
                <label for="password">Password</label>
                <input type="password" name="password" />
                <input type="submit" value="Login" />
            </form>

            <a href="/Authentication/Register">
                Register a free account !
            </a>
        ';
    }

    public  function Register() {
        echo '<form id="regacc">
    <input type="text" name="username" />
    <input type="password" name="password" />
    <input type="submit" value="Register!" />
    </form>

    <span id="status"></span>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#regacc").submit(function(event) {
                event.preventDefault();
                var userForm = $(event.target).serialize();

                $.ajax({
                   url:"/Auth/Register",
                   method: "POST",
                   data: userForm
                }).success(function() {
                $("#status").html("Account registered successful.")
                }).error(function(err) {
                $("#status").html("Following error occured: " + err);
            });
            });
    });
    </script>';
    }

    public function View() {
        if(!isset($_SESSION['User'])) {

        }

        echo '<div id="shoutContainer">

            </div>

            <script id="shoutTmpl" type="text/ractive">
                <div on-mousewheel="scrollMsg" data-page="{{page}}" data-pagesize={{pageSize}} id="messages" style="border: 1px solid black; overflow-y: scroll; max-height: 400px; min-height: 400px;">
                {{#shouts}}
                    <strong>{{sentDate}} {{sentTime}} {{Owner.Username}} </strong>
                    <p on-mousewheel="return false;">
                        {{MessageText}}
                        {{#isOwner}}
                            <img data-messageId="{{id}}" on-click="deleteMsg" src="/images/delete.png" style="width: 15px; height: 15px;" />
                        {{/isOwner}}
                    </p>
                {{/shouts}}
                </div>
                <form>
                    <input style="width: 30%" type="text" name="msg" value={{message}} />
                    <input type="submit" on-click="sendmsg"/>
                </form>
            </script>

            <script type="text/javascript">
                $(document).ready(function() {
                    $.ajax({
                       url:"/Shout/read",
                       method: "GET",
                       data: {page: 1, pagesize : 10}
                    }).done(function(shouts) {

                        var ractive = new Ractive({
                            el: "shoutContainer",
                            template: "#shoutTmpl"
                        });

                        ractive.addDateTimeStamps = function(shouts) {
                            for(var i= 0; i < shouts.length; i++) {
                                var current = new Date(shouts[i].Time);
                                shouts[i].sentTime = current.toLocaleTimeString();
                                shouts[i].sentDate = current.toLocaleDateString();
                            }
                        }

                        ractive
                                .addDateTimeStamps(shouts.Messages);
                        shouts.Messages.reverse();
                        ractive.set("shouts",shouts.Messages);

                        // set the page to 2, because this value indicates, which page will be loaded next.
                        if(shouts.TotalCount > 2 * 10) {
                            // crap.
                            ractive.set("page",2);
                        } else {
                            ractive.set("page",1);
                        }


                        ractive.set("pageSize",10);
                        // after loading the messages, scroll to the lates message.
                        var messages = $("#messages");
                        messages.scrollTop(messages[0].scrollHeight);

                        ractive.updateMessages = function(scrollToBottom, callback) {
                            var page = $("#messages").attr("data-page");
                            var pageSize = $("#messages").attr("data-pagesize");
                            $.ajax({
                                url:"/Shout/read",
                                method: "GET",
                                data: {page: 1, pagesize: (page * pageSize)}
                            }).done(function(shouts) {
                                shouts.Messages.reverse();
                                ractive.addDateTimeStamps(shouts.Messages);
                                ractive.set("shouts", shouts.Messages);
                                if(scrollToBottom) {
                                    var messages = $("#messages");
                                    messages.scrollTop(messages[0].scrollHeight);
                                }

                                // If there are more messages to load, return true.

                                if((page * pageSize) > shouts.TotalCount) {
                                    callback && callback(false);
                                } else {
                                    callback && callback(true);
                                }
                            });
                        };
                        ractive.updateTimer = setInterval(function() {
                            ractive.updateMessages();
                        }, 10000);

                        ractive.on("sendmsg", function(event) {
                            event
                                    .original
                                    .preventDefault();
                            var message = ractive.get("message");
                            $.ajax({
                                url:"/Shout/create",
                                method: "POST",
                                data: {msg: message}
                            }).done(function(res) {
                                ractive.updateMessages(true);
                                ractive.set("message","");

                            });
                        });

                        ractive.on("deleteMsg", function(event) {
                            var msgId = $(event.node).attr("data-messageId");
                            $.ajax({
                                url: "/Shout/destroy/" + msgId,
                                method: "DELETE"
                                //data: {id: msgId}
                            }).done(function(res) {
                                if(res.success) {
                                   var shouts = ractive.get("shouts");
                                   for(var i = 0; i < shouts.length;i++) {
                                       if(shouts[i].id == msgId) {
                                           shouts.splice(i, 1);
                                       }
                                   }
                                    ractive.update("shouts");

                                }
                            })
                        });

                        ractive.on("scrollMsg", function(event) {
                            if(
                               event.original.target == event.node
                               && event.original.target.scrollTop == 0) {
                                var page = ractive.get("page");
                                ractive.updateMessages(false, function(morePages) {
                                    if(morePages) {
                                        ractive.set("page", page +1);
                                    }
                                });
                                }
                            }
                        );
                    });
                });

            </script>';
    }
}

?>
