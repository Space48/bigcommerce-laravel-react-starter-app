import React from 'react';
import {Switch, useLocation} from 'react-router-dom';
import {CSSTransition, TransitionGroup} from 'react-transition-group';

interface Props {
  children: React.ReactNode;
}

const AnimatedSwitch = ({children} : Props) => {
  const location = useLocation();

  return (
    <TransitionGroup>
      <CSSTransition
        key={location.pathname}
        classNames="fade"
        timeout={300}
      >
        <Switch location={location}>
          {children}
        </Switch>
      </CSSTransition>
    </TransitionGroup>
  )
}

export default AnimatedSwitch;
