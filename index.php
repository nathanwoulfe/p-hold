<!DOCTYPE html>
    <!--
        Twenty 1.0 by HTML5 UP
        html5up.net | @n33co
        Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
    -->
<html>
    <head>
        <?php include_once('head.php') ?>
    </head>
    <body class="index loading">
        <section id="banner">
            <div class="inner">
                <header><h2>p-hold</h2></header>
                <p>Quick, easy and attractive<br>placeholder images.</p>
                <footer>
                    <ul class="buttons vertical">
                        <li><a href="#main" class="button fit scrolly">MORE</a></li>
                    </ul>
                </footer>
            </div>
        </section>
        <article id="main">
            <header class="special container">
                <span class="icon fa-star-o"></span>
                <h2>It's another placeholder service.</h2>
                <p>Only this one is more configurable and returns the most<br>interesting CC-licenced images on Flickr.</p>
            </header>
            <section class="wrapper style1 container special">
                <div class="row">
                    <div class="4u">
                        <section>
                            <span class="icon feature fa-picture-o"></span>
                            <header>
                                <h3>Default - /w/h or /w</h3>
                            </header>
                            <p>Set the image <code>src</code> attribute to <code class="block">p-hold.com/400/300</code> to render a non-ugly image at the given dimensions.</p>                        </section>
                    </div>
                    <div class="4u">
                        <section>
                            <span class="icon feature fa-picture-o"></span>
                            <header>
                                <h3>Keyword - /q/w/h</h3>
                            </header>
                            <p>Set the image <code>src</code> attribute to <code class="block">p-hold.com/kitten/w/h</code> to render an image of a kitten at the given dimensions.</p>                        </section>
                    </div>
                    <div class="4u">                        <section>                            <span class="icon feature fa-picture-o"></span>
                            <header>
                                <h3>Filter - /w/h/f or /q/w/h/f</h3>
                            </header>
                            <p>Append a hex code, 'gray' or 'blur' to the end of the source URL for a colorized, grayscale or gaussian-blurred image.</p>
                        </section>                    </div>                </div>                <div class="row">
                    <div class="4u">
                        <section>
                            <span class="icon feature fa-picture-o"></span>
                            <header>
                                <h3>Advertising - /x</h3>
                            </header>
                            <p>Set the image <code>src</code> attribute to <code class="block">p-hold.com/x</code> where x is the ad unit abbreviation.</p>                        </section>
                    </div>                    <div class="4u">
                        <section>
                            <header>
                                <h3>Valid ad unit abbreviations</h3>
                            </header>
                            <ul>                                <li>/MREC - medium rectangle - 300x250</li>                                <li>/LREC - large rectangle - 336x280</li>                                <li>/LB - leaderboard - 728x90</li>                                <li>/LLB - large leaderboard - 970x90</li>                                <li>/SKY - skyscraper - 120x600</li>                                <li>/WSKY - wide skyscraper - 160x600</li>                                <li>/LSKY - large skyscraper - 300x600</li>                            </ul>                        </section>
                    </div>                    <div class="4u">
                        <section>
                            <header>
                                <h3>&nbsp;</h3>
                            </header>
                            <ul>                                <li>/S - square - 250x250</li>                                <li>/SS - small square - 200x200</li>                                <li>/B - banner - 468x60</li>                                <li>/HB - half banner - 234x60</li>                                <li>/VB - vertical banner - 120x240</li>                                <li>/BTN - button - 125x125</li>                            </ul>                        </section>
                    </div>                </div>                <div class="row">                    <div class="note">                        <p>Note: including a keyword in the image URL will be slower than the other methods as it involves realtime polling of the Flickr API. Querying for a given size (with or without a filter) uses local copies of images, removing the extra request overhead. But keywords means kittens, so it's a worthwhile tradeoff.</p>                    </div>                </div>            </section>            <section class="wrapper style2 container special examples">                <header class="major">                    <h2>Examples</h2>                </header>                <div class="row">                    <div class="4u">                        <code class="block">p-hold.com/200/300</code>                        <img src="/200/300" alt="300x400 example" />                    </div>                    <div class="4u">                        <code class="block">p-hold.com/200</code>                        <img src="/200" alt="200x200 example" />                                            </div>                    <div class="4u">                        <code class="block">p-hold.com/sheep/200</code>                        <img src="/sheep/200" alt="200x200 keyword example" />                                            </div>                </div>                <div class="row">                    <div class="4u">                        <code class="block">p-hold.com/200/blur</code>                        <img src="/200/blur" alt="blur example" />                    </div>                    <div class="4u">                        <code class="block">p-hold.com/sheep/200/grey</code>                        <img src="/sheep/200/grey" alt="grey example" />                                          </div>
                    <div class="4u">                        <code class="block">p-hold.com/200/01553b</code>                        <img src="/200/01553b" alt="colorised example" />                                            </div>                </div>            </section>            <section class="wrapper style1 container special counter">                <header class="major">                    <h2><span class="image-count"><span></span></span><br>images served</h2>                </header>                      </section>
            <section class="wrapper style3 container special">
                <header class="major">
                    <h2>Current <strong>photoset</strong></h2>
                </header>
                <div class="row thumbs"></div>
            </section>
        </article>
        <section id="cta">            <header>                <h2>Thoughts, ideas, <strong>suggestions</strong>?</h2>                <p>Tell us what you really think...</p>            </header>            <footer>                <ul class="buttons">                    <li><a href="mailto:nathan@nathanw.com.au" class="button special">Drop us a line</a></li>                    <li><a href="http://www.twitter.com/nathanwoulfe" class="button">Twitter</a></li>                </ul>
            </footer>
        </section>        <?php include_once('foot.php') ?>
        <?php include_once('ga.php') ?>
    </body>
</html>