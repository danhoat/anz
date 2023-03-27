import { VFC } from 'react';

type Props = {
};

export const Buttons: VFC<Props> = () => {
  return (
    <div className="brick-buttons">
      <div className="brick-buttons__button-unit brick-buttons__button-unit-submit">
        <button className="brick-buttons__button-submit">
          <span className="brick-buttons__button-inner">確認画面へ進む</span>
        </button>
      </div>
    </div>
  );
}
