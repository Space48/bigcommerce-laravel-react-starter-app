import React from 'react';
import {Link as BigLink} from "@bigcommerce/big-design";
import {Link as RouterLink, useHistory} from "react-router-dom"

interface Props {
  style?: Record<string, string>;
  to: string | {
    pathname: string;
    state?: any;
  };
  children?: React.ReactNode;
}

/**
 * Add client-side navigation support to BigCommerce Link.
 * @param props
 */
const Link = (props: Props) => {
  const {to, children} = props;

  const history = useHistory();
  const href = typeof to === "object" ? to.pathname : to;

  return (
    <RouterLink
      to={to}
      component={() => <BigLink href={href} {...props} onClick={(event) => {
        event.preventDefault();
        history.push(to);
      }}>{children}</BigLink>}/>
  );
}

export default Link;
