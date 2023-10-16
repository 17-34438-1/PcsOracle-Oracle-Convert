<div class="container">

    <div class="row">
        <div class="col-md-12">
            <h1>Blog single post</h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo site_url('Welcome/')?>">Home</a><span class="divider">/</span></li>
                <li><a href="<?php echo site_url('WebController/Blog')?>">Blog</a><span class="divider">/</span></li>
                <li class="active">Blog post</li>
            </ul>
        </div>

        <!-- =========================Start Col left section ============================= -->
        <aside class="col-md-4 col-sm-4">
            <div class="col-left">
                <div class="sidebar">

                    <div class="widget">
                        <div class="form-group">
                            <form class="form-search form-inline">
                                <input type="text" class="input-medium form-control">
                                <button type="submit" class="button_medium" style="position:relative; top:2px;">Search</button>
                            </form>
                        </div>
                    </div><!-- End Search -->

                    <div class="widget">
                        <h4>Text widget</h4>
                        <p>
                            Fusce feugiat malesuada odio. Morbi nunc odio, gravida at, cursus nec, luctus a, lorem. Maecenas tristique orci ac sem. Duis ultricies pharetra magna. Donec accumsan malesuada orci. Donec sit amet eros. Lorem ipsum dolor sit amet, consectetuer adipiscing elit.
                        </p>
                    </div><!-- End widget -->

                    <hr>

                    <div class="widget">
                        <h4>Recent post</h4>
                        <ul class="recent_post">
                            <li>
                                <i class="icon-calendar-empty"></i> 16th July, 2020 <div><a href="#">It is a long established fact that a reader will be distracted </a></div>
                            </li>
                            <li>
                                <i class="icon-calendar-empty"></i> 16th July, 2020 <div><a href="#">It is a long established fact that a reader will be distracted </a></div>
                            </li>
                            <li>
                                <i class="icon-calendar-empty"></i> 16th July, 2020 <div><a href="#">It is a long established fact that a reader will be distracted </a></div>
                            </li>
                        </ul>
                    </div><!-- End widget -->

                    <hr>
                    <div class="widget tags">
                        <h4>Tags</h4>
                        <a href="#">Lorem ipsum</a>
                        <a href="#">Dolor</a>
                        <a href="#">Long established</a>
                        <a href="#">Sit amet</a>
                        <a href="#">Latin words</a>
                        <a href="#">Excepteur sint</a>
                    </div><!-- End widget -->

                </div><!-- end siedebar  -->
            </div><!-- end  col left -->
            <p><img src="<?php echo ASSETS_WEB_PATH?>fimg/banner.jpg" alt="Banner" class="img-rounded img-responsive" ></p>
        </aside>

        <!-- =========================Start Col right section ============================= -->
        <section class="col-md-8 col-sm-8">
            <div class="col-right">
                <div class="post">
                    <h2><a href="blog_post.html">Duis aute irure dolor in reprehenderit</a></h2>
                    <img src="<?php echo ASSETS_WEB_PATH?>fimg/blog-1.jpg" alt="" class="img-responsive">
                    <div class="post_info clearfix">
                        <div class="post-left">
                            <ul>
                                <li><i class="icon-calendar-empty"></i>On <span>12 Nov 2020</span></li>
                                <li><i class="icon-user"></i>By <a href="#">John Smith</a></li>
                                <li><i class="icon-tags"></i>Tags <a href="#">Works</a><a href="#">Personal</a></li>
                            </ul>
                        </div>
                        <div class="post-right"><i class="icon-comments"></i><a href="#">25 </a>Comments</div>
                    </div>
                    <p>
                        Praesent vestibulum molestie lacus. Aenean nonummy hendrerit mauris. Phasellus porta. Fusce suscipit varius mi nascetur ridiculus mus. Nulla dui. Fusce feugiat malesuada odio. Morbi nunc odio, gravida at, cursus nec, luctus a, lorem. Maecenas tristique orci ac sem. Duis ultricies pharetra magna. Donec accumsan malesuada orci. Donec sit amet eros. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Mauris fermentum dictum magna. Sed laoreet aliquam leo.
                    </p>
                    <p><a href="blog_post.html" class="button_medium">Read more</a></p>
                </div><!-- end post -->
                <hr>

                <h4>4 comments</h4>
                <div id="comments">
                    <ol>

                        <li>
                            <div class="avatar"><a href="#"><img src="<?php echo ASSETS_WEB_PATH?>fimg/avatar1.jpg" alt="" class="img-responsive"></a></div>
                            <div class="comment_right clearfix">
                                <div class="comment_info">Posted by <a href="#">Anna Smith</a><span>|</span> 25 apr 2019 <span>|</span><a href="#">Reply</a></div>
                                <p>
                                    Nam cursus tellus quis magna porta adipiscing. Donec et eros leo, non pellentesque arcu. Curabitur vitae mi enim, at vestibulum magna. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed sit amet sem a urna rutrumeger fringilla. Nam vel enim ipsum, et congue ante.
                                </p>
                            </div>
                            <ul>
                                <li>
                                    <div class="avatar"><a href="#"><img src="<?php echo ASSETS_WEB_PATH?>fimg/avatar2.jpg" alt="" class="img-responsive"></a></div>
                                    <div class="comment_right clearfix">
                                        <div class="comment_info">Posted by <a href="#">Tom Sawyer</a><span>|</span> 25 apr 2019 <span>|</span><a href="#">Reply</a></div>
                                        <p>
                                            Nam cursus tellus quis magna porta adipiscing. Donec et eros leo, non pellentesque arcu. Curabitur vitae mi enim, at vestibulum magna. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed sit amet sem a urna rutrumeger fringilla. Nam vel enim ipsum, et congue ante.
                                        </p>
                                        <p>
                                            Aenean iaculis sodales dui, non hendrerit lorem rhoncus ut. Pellentesque ullamcorper venenatis elit idaipiscingi Duis tellus neque, tincidunt eget pulvinar sit amet, rutrum nec urna. Suspendisse pretium laoreet elit vel ultricies. Maecenas ullamcorper ultricies rhoncus. Aliquam erat volutpat.
                                        </p>
                                    </div>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <div class="avatar"><a href="#"><img src="<?php echo ASSETS_WEB_PATH?>fimg/avatar3.jpg" alt="" class="img-responsive"></a></div>
                            <div class="comment_right clearfix">
                                <div class="comment_info">Posted by <a href="#">Adam White</a><span>|</span> 25 apr 2019 <span>|</span><a href="#">Reply</a></div>
                                <p>Cursus tellus quis magna porta adipiscin</p>
                            </div>
                        </li>

                    </ol>
                </div><!-- End Comments -->

                <h4>Leave a comment</h4>
                <form action="#" method="post">
                    <input class="form-control" type="text" name="name" value="Name" onfocus="if (this.value == 'Name') this.value = '';" onblur="if (this.value == '') this.value = 'Name';"/>
                    <input class="form-control" type="text" name="mail" value="Email" onfocus="if (this.value == 'Email') this.value = '';" onblur="if (this.value == '') this.value = 'Email';"/>
                    <textarea name="message" class="form-control"  rows="4" onfocus="if (this.value == 'Message...') this.value = '';" onblur="if (this.value == '') this.value = 'Message...';">Message...</textarea>
                    <input type="reset" class="button_medium" value="Clear form"/>
                    <input type="submit" class="button_medium" value="Post Comment"/>
                </form>

            </div><!-- end col-right-->
        </section><!-- end section-->
    </div><!-- end row-->
</div><!-- end container-->
