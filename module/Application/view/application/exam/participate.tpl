{extends "../../_common/base.tpl"}

{block name="content"}
	<section id="content">
		<div class="container">
        	<div class="block-header">
            	<h2>{$firstName} {$lastName}</h2>
            </div>
            
            {if $enableMessage eq false}
             	<div class="card blue">
            
            		<div class="card-header bgm-purple">
                    	<h2>NOTIFICA</h2>
                    </div>
                    
                    <div class="card-body card-padding bgm-white">
                        <p class="lead">{$message}</p>
                    </div>
                </div>
			{/if}
                
			<div class="card">
            	<div class="card-header">
                	<h2>{$examName}</h2>
				</div>
                
                <div class="card-body card-padding">
                	<h1>{$itemTitle}</h1>
                            <p>Suspendisse vel quam malesuada, aliquet sem sit amet, fringilla elit. Morbi tempor tincidunt tempor. Etiam id turpis viverra, vulputate sapien nec, varius sem. Curabitur ullamcorper fringilla eleifend. In ut eros hendrerit est consequat posuere et at velit.</p>
                    
                            <div class="clearfix"></div>
                    
                            <h2>This is a Heading 2</h2>
                            <p>In nec rhoncus eros. Vestibulum eu mattis nisl. Quisque viverra viverra magna nec pulvinar. Maecenas pellentesque porta augue, consectetur facilisis diam porttitor sed. Suspendisse tempor est sodales augue rutrum tincidunt. Quisque a malesuada purus.</p>
                    
                            <div class="clearfix"></div>
                    
                            <h3>This is a Heading 3</h3>
                            <p>Vestibulum auctor tincidunt semper. Phasellus ut vulputate lacus. Suspendisse ultricies mi eros, sit amet tempor nulla varius sed. Proin nisl nisi, feugiat quis bibendum vitae, dapibus in tellus.</p>
                    
                            <div class="clearfix"></div>
                    
                            <h4>This is a Heading 4</h4>
                            <p>Nulla et mattis nunc. Curabitur scelerisque commodo condimentum. Mauris blandit, velit a consectetur egestas, diam arcu fermentum justo, eget ultrices arcu eros vel erat.</p>
                    
                            <div class="clearfix"></div>
                    
                            <h5>This is a Heading 5</h5>
                            <p>Quisque nec turpis at urna dictum luctus. Suspendisse convallis dignissim eros at volutpat. In egestas mattis dui. Aliquam mattis dictum aliquet. Nulla sapien mauris, eleifend et sem ac, commodo dapibus odio. Vivamus pretium nec odio cursus elementum. Suspendisse molestie ullamcorper ornare.</p>
                    
                            <div class="clearfix"></div>
                    
                            <h6>This is a Heading 6</h6>
                            <p>Donec ultricies, lacus id tempor condimentum, orci leo faucibus sem, a molestie libero lectus ac justo. ultricies mi eros, sit amet tempor nulla varius sed. Proin nisl nisi, feugiat quis bibendum vitae, dapibus in tellus.</p>
                        </div>
                    </div>
                
                    <div class="card">
                        <div class="card-header">
                            <h2>Inline text elements</h2>
                    
                            <ul class="actions">
                                <li class="dropdown action-show">
                                    <a href="" data-toggle="dropdown">
                                        <i class="md md-more-vert"></i>
                                    </a>
                    
                                    <div class="dropdown-menu pull-right">
                                        <p class="p-20">
                                            You can put anything here
                                        </p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    
                        <div class="card-body card-padding">
                            <div class="row">
                                <div class="col-sm-4 m-b-25">
                                    <p class="c-black">Marked text</p>
                                    <p>For highlighting a run of text due to its relevance in another context, use the 'mark' tag.</p>
                                    <mark>This is marked text</mark>
                                </div>
                    
                                <div class="col-sm-4 m-b-25">
                                    <p class="c-black">Deleted Text</p>
                                    <p>For indicating blocks of text that have been deleted use the 'del' tag.</p>
                                    <del>This is Deleted Text</del>
                                </div>
                    
                                <div class="col-sm-4 m-b-25">
                                    <p class="c-black">Strikethrough text</p>
                                    <p>For indicating blocks of text that are no longer relevant use the 's' tag.</p>
                                    <s>This is Deleted Text</s>
                                </div>
                    
                                <div class="col-sm-4 m-b-25">
                                    <p class="c-black">Inserted Text</p>
                                    <p>For indicating additions to the document use the 'ins' tag.</p>
                                    <ins>This is Inserted Text</ins>
                                </div>
                    
                                <div class="col-sm-4 m-b-25">
                                    <p class="c-black">Underlined Text</p>
                                    <p>To underline text use the 'u' tag.</p>
                                    <u>This is Underlined Text</u>
                                </div>
                    
                                <div class="col-sm-4 m-b-25">
                                    <p class="c-black">Small Text</p>
                                    <p>For de-emphasizing inline or blocks of text, use the 'small' tag.</p>
                                    <small>This is Small Text</small>
                                </div>
                    
                                <div class="col-sm-4">
                                    <p class="c-black">Bold Text</p>
                                    <p>For emphasizing a snippet of text with a heavier font-weight.</p>
                                    <strong>This is Bold Text</strong>
                                </div>
                    
                                <div class="col-sm-4">
                                    <p class="c-black">Underline Text</p>
                                    <p>For emphasizing a snippet of text with italics.</p>
                                    <em>This is Underline Text</em>
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-header">
                                    <h2>Helper Classes</h2>
                    
                                    <ul class="actions">
                                        <li class="dropdown action-show">
                                            <a href="" data-toggle="dropdown">
                                                <i class="md md-more-vert"></i>
                                            </a>
                    
                                            <div class="dropdown-menu pull-right">
                                                <p class="p-20">
                                                    You can put anything here
                                                </p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                    
                                <div class="card-body card-padding">
                                    <p class="c-black">Alignment Classes</p>
                                    <p>Easily realign text to components with text alignment classes.</p>
                    
                                    <p class="text-left">Left aligned text.</p>
                                    <p class="text-center">Center aligned text.</p>
                                    <p class="text-right">Right aligned text.</p>
                                    <p class="text-justify">Justified text.</p>
                                    <p class="text-nowrap">No wrap text.</p>
                    
                                    <p class="c-black m-t-25">Transformation classes</p>
                                    <p>Transform text in components with text capitalization classes.</p>
                    
                                    <p class="text-lowercase">Lowercased text.</p>
                                    <p class="text-uppercase">Uppercased text.</p>
                                    <p class="text-capitalize">Capitalized text.</p>
                                </div>
                            </div>
                        </div>
                    
                        <div class="col-sm-6">
                            <div class="card" style="min-height: 427px">
                                <div class="card-header">
                                    <h2>Abbreviations</h2>
                    
                                    <ul class="actions">
                                        <li class="dropdown action-show">
                                            <a href="" data-toggle="dropdown">
                                                <i class="md md-more-vert"></i>
                                            </a>
                    
                                            <div class="dropdown-menu pull-right">
                                                <p class="p-20">
                                                    You can put anything here
                                                </p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                    
                                <div class="card-body card-padding">
                                    <p>Stylized implementation of HTML's 'abbr' element for abbreviations and acronyms to show the expanded version on hover. Abbreviations with a 'title' attribute have a light dotted bottom border and a help cursor on hover, providing additional context on hover and to users of assistive technologies.</p>
                    
                                    <p class="c-black m-t-20">Basic abbreviation</p>
                                    <p>An abbreviation of the word attribute is <abbr title="attribute">attr</abbr>.</p>
                    
                                    <p class="c-black m-t-20">Initialism</p>
                                    <p>Add <abbr title="Initialism" class="initialism">Initialism</abbr> to an abbreviation for a slightly smaller font-size.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h2>Blockquotes</h2>
                    
                            <ul class="actions">
                                <li class="dropdown action-show">
                                    <a href="" data-toggle="dropdown">
                                        <i class="md md-more-vert"></i>
                                    </a>
                    
                                    <div class="dropdown-menu pull-right">
                                        <p class="p-20">
                                            You can put anything here
                                        </p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    
                        <div class="card-body card-padding">
                            <p class="m-b-25">For quoting blocks of content from another source within your document.</p>
                    
                            <blockquote class="m-b-25">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                            </blockquote>
                    
                            <blockquote class="m-b-25">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                                <footer>Someone famous in <cite title="Source Title">Source Title</cite></footer>
                            </blockquote>
                    
                            <blockquote class="blockquote-reverse m-b-25">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                                <footer>Someone famous in <cite title="Source Title">Source Title</cite></footer>
                            </blockquote>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h2>List</h2>
                    
                            <ul class="actions">
                                <li class="dropdown action-show">
                                    <a href="" data-toggle="dropdown">
                                        <i class="md md-more-vert"></i>
                                    </a>
                    
                                    <div class="dropdown-menu pull-right">
                                        <p class="p-20">
                                            You can put anything here
                                        </p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    
                        <div class="card-body card-padding">
                            <div class="row">
                                <div class="col-sm-4 m-b-25">
                                    <p class="c-black">Unordered</p>
                    
                                    <ul>
                                        <li>Lorem ipsum dolor sit amet</li>
                                        <li>Consectetur adipiscing elit</li>
                                        <li>Integer molestie lorem at massa</li>
                                        <li>Facilisis in pretium nisl aliquet</li>
                                        <li>Nulla volutpat aliquam velit
                                            <ul>
                                                <li>Phasellus iaculis neque</li>
                                                <li>Purus sodales ultricies</li>
                                                <li>Vestibulum laoreet porttitor sem</li>
                                                <li>Ac tristique libero volutpat at</li>
                                            </ul>
                                        </li>
                                        <li>Faucibus porta lacus fringilla vel</li>
                                        <li>Aenean sit amet erat nunc</li>
                                        <li>Eget porttitor lorem</li>
                                    </ul>
                                </div>
                    
                                <div class="col-sm-4 m-b-25">
                                    <p class="c-black">Ordered</p>
                    
                                    <ol>
                                        <li>Lorem ipsum dolor sit amet</li>
                                        <li>Consectetur adipiscing elit</li>
                                        <li>Integer molestie lorem at massa</li>
                                        <li>Facilisis in pretium nisl aliquet</li>
                                        <li>Nulla volutpat aliquam velit</li>
                                        <li>Faucibus porta lacus fringilla vel</li>
                                        <li>Aenean sit amet erat nunc</li>
                                        <li>Eget porttitor lorem</li>
                                        <li>Integer molestie lorem at massa</li>
                                        <li>Faucibus porta lacus fringilla vel</li>
                                        <li>Spretium nisl aliquet lorem ipsum</li>
                                        <li>Linking best ttoth bellorem</li>
                                    </ol>
                                </div>
                    
                                <div class="col-sm-4 m-b-25">
                                    <p class="c-black">Ordered - Roman</p>
                    
                                    <ol type="i">
                                        <li>Lorem ipsum dolor sit amet</li>
                                        <li>Consectetur adipiscing elit</li>
                                        <li>Integer molestie lorem at massa</li>
                                        <li>Facilisis in pretium nisl aliquet</li>
                                        <li>Nulla volutpat aliquam velit</li>
                                        <li>Phasellus iaculis neque</li>
                                        <li>Purus sodales ultricies</li>
                                        <li>Vestibulum laoreet porttitor sem</li>
                                        <li>Ac tristique libero volutpat at</li>
                                        <li>Faucibus porta lacus fringilla vel</li>
                                        <li>Aenean sit amet erat nunc</li>
                                        <li>Eget porttitor lorem</li>
                                    </ol>
                                </div>
                    
                                <div class="col-sm-4 m-b-25">
                                    <p class="c-black">Custom - 1</p>
                    
                                    <ul class="clist clist-angle">
                                        <li>Lorem ipsum dolor sit amet</li>
                                        <li>Consectetur adipiscing elit</li>
                                        <li>Integer molestie lorem at massa</li>
                                        <li>Facilisis in pretium nisl aliquet</li>
                                        <li>Nulla volutpat aliquam velit</li>
                                        <li>Phasellus iaculis neque</li>
                                        <li>Purus sodales ultricies</li>
                                        <li>Vestibulum laoreet porttitor sem</li>
                                        <li>Ac tristique libero volutpat at</li>
                                        <li>Faucibus porta lacus fringilla vel</li>
                                        <li>Aenean sit amet erat nunc</li>
                                        <li>Eget porttitor lorem</li>
                                    </ul>
                                </div>
                    
                                <div class="col-sm-4 m-b-25">
                                    <p class="c-black">Custom - 2</p>
                    
                                    <ul class="clist clist-check">
                                        <li>Lorem ipsum dolor sit amet</li>
                                        <li>Consectetur adipiscing elit</li>
                                        <li>Integer molestie lorem at massa</li>
                                        <li>Facilisis in pretium nisl aliquet</li>
                                        <li>Nulla volutpat aliquam velit</li>
                                        <li>Phasellus iaculis neque</li>
                                        <li>Purus sodales ultricies</li>
                                        <li>Vestibulum laoreet porttitor sem</li>
                                        <li>Ac tristique libero volutpat at</li>
                                        <li>Faucibus porta lacus fringilla vel</li>
                                        <li>Aenean sit amet erat nunc</li>
                                        <li>Eget porttitor lorem</li>
                                    </ul>
                                </div>
                    
                                <div class="col-sm-4 m-b-25">
                                    <p class="c-black">Custom - 3</p>
                    
                                    <ul class="clist clist-star">
                                        <li>Lorem ipsum dolor sit amet</li>
                                        <li>Consectetur adipiscing elit</li>
                                        <li>Integer molestie lorem at massa</li>
                                        <li>Facilisis in pretium nisl aliquet</li>
                                        <li>Nulla volutpat aliquam velit</li>
                                        <li>Phasellus iaculis neque</li>
                                        <li>Purus sodales ultricies</li>
                                        <li>Vestibulum laoreet porttitor sem</li>
                                        <li>Ac tristique libero volutpat at</li>
                                        <li>Faucibus porta lacus fringilla vel</li>
                                        <li>Aenean sit amet erat nunc</li>
                                        <li>Eget porttitor lorem</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
{/block}