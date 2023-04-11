<?php

namespace QMS4\Action\CustomBlock;


class RegisterBlock
{
	/**
	 * @return    void
	 */
	public function __invoke()
	{
		( new \QMS4\Block\AreaList )->register();
		( new \QMS4\Block\EventCalendar )->register();
		( new \QMS4\Block\IncludeBlock )->register();
		( new \QMS4\Block\Infotable )->register();
		( new \QMS4\Block\Link )->register();
		( new \QMS4\Block\MegaMenu )->register();
		( new \QMS4\Block\MonthlyPosts )->register();
		( new \QMS4\Block\PanelMenu )->register();
		( new \QMS4\Block\PostList )->register();
		( new \QMS4\Block\RestrictedArea )->register();
		( new \QMS4\Block\TermList )->register();
		( new \QMS4\Block\Timetable )->register();
		( new \QMS4\Block\UserVoice )->register();
	}
}
