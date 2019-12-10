import React from 'react';
import PropTypes from 'prop-types';

const Info = (props) => {
  const {
    address,
    facebook,
    email,
    phoneNumber,
  } = props;

  const getRow = (icon, title, link, text) => (
    <li className="info__row">
      <i className={`info__icon icon-${icon}`} />
      <div className="info__box">
        <h3 className="info__title">{title}</h3>
        <a href={link} className="link -hover-underline" target="_blank" rel="noopener noreferrer">
          {text}
        </a>
      </div>
    </li>
  );

  return (
    <div className="info">
      <h2 className="contractor__heading">Useful information</h2>
      <ul className="info__list">
        {address && getRow('location', 'location', `https://maps.google.com/?q=${address}`, address)}
        {email && getRow('envelope', 'Email Address', `mailto:${email}`, email)}
        {facebook && getRow('facebook', 'Facebook', `https://facebook.com/${facebook}`, facebook)}
        {phoneNumber && getRow('phone', 'phoneNumber', `tel:${phoneNumber}`, phoneNumber)}
      </ul>
    </div>
  );
};

Info.propTypes = {
  address: PropTypes.string,
  facebook: PropTypes.string,
  email: PropTypes.string,
  phoneNumber: PropTypes.string,
};

Info.defaultProps = {
  address: '',
  facebook: '',
  email: '',
  phoneNumber: '',
};

export default Info;